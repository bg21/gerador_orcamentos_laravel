<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest access is restricted.
     */
    public function test_guests_cannot_access_quotes(): void
    {
        $this->get(route('quotes.index'))->assertRedirect(route('login'));
        $this->get(route('quotes.create'))->assertRedirect(route('login'));
        $this->post(route('quotes.store'), [])->assertRedirect(route('login'));
    }

    /**
     * Test authenticated user can perform Quote CRUD.
     */
    public function test_authenticated_user_can_perform_quote_crud(): void
    {
        $user = User::factory()->create();
        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'Cliente do Teste de Orçamento',
            'email' => 'cliente.quote@example.com'
        ]);

        // 1. Create quote
        $response = $this->actingAs($user)->post(route('quotes.store'), [
            'client_id' => $client->id,
            'status' => 'draft',
            'issue_date' => '2026-06-01',
            'due_date' => '2026-06-15',
            'discount' => '50,00', // BRL format
            'notes' => 'Observações do orçamento de teste',
            'items' => [
                [
                    'description' => 'Serviço de Programação',
                    'quantity' => 2,
                    'unit_price' => '150,00', // BRL format -> 150.00
                ],
                [
                    'description' => 'Consultoria Técnica',
                    'quantity' => 1,
                    'unit_price' => '200,00', // BRL format -> 200.00
                ]
            ]
        ]);

        $response->assertRedirect(route('quotes.index'));
        
        // Assert sequence: First quote of the year 2026 should be ORC-2026-0001
        $expectedQuoteNumber = "ORC-" . now()->year . "-0001";

        $this->assertDatabaseHas('quotes', [
            'user_id' => $user->id,
            'client_id' => $client->id,
            'quote_number' => $expectedQuoteNumber,
            'status' => 'draft',
            'discount' => 50.00,
            // (2 * 150) + (1 * 200) - 50 = 300 + 200 - 50 = 450
            'total_amount' => 450.00,
        ]);

        $quote = Quote::first();

        $this->assertDatabaseHas('quote_items', [
            'quote_id' => $quote->id,
            'description' => 'Serviço de Programação',
            'quantity' => 2,
            'unit_price' => 150.00,
            'total_price' => 300.00,
        ]);

        $this->assertDatabaseHas('quote_items', [
            'quote_id' => $quote->id,
            'description' => 'Consultoria Técnica',
            'quantity' => 1,
            'unit_price' => 200.00,
            'total_price' => 200.00,
        ]);

        // 2. View details (show)
        $this->actingAs($user)->get(route('quotes.show', $quote))->assertOk();

        // 3. View edit page
        $this->actingAs($user)->get(route('quotes.edit', $quote))->assertOk();

        // 4. Update quote (remove one item and change discount)
        $response = $this->actingAs($user)->put(route('quotes.update', $quote), [
            'client_id' => $client->id,
            'status' => 'approved',
            'issue_date' => '2026-06-01',
            'due_date' => '2026-06-15',
            'discount' => '20,00',
            'notes' => 'Notas atualizadas',
            'items' => [
                [
                    'description' => 'Serviço de Programação',
                    'quantity' => 3,
                    'unit_price' => '150,00',
                ]
            ]
        ]);

        $response->assertRedirect(route('quotes.index'));
        
        $this->assertDatabaseHas('quotes', [
            'id' => $quote->id,
            'status' => 'approved',
            'discount' => 20.00,
            // (3 * 150) - 20 = 450 - 20 = 430
            'total_amount' => 430.00,
            'notes' => 'Notas atualizadas',
        ]);

        // Old items should be removed, new items added
        $this->assertEquals(1, $quote->items()->count());

        // 5. Delete quote
        $response = $this->actingAs($user)->delete(route('quotes.destroy', $quote));
        $response->assertRedirect(route('quotes.index'));
        
        $this->assertDatabaseMissing('quotes', ['id' => $quote->id]);
        $this->assertDatabaseMissing('quote_items', ['quote_id' => $quote->id]);
    }

    /**
     * Test multi-tenant isolation for quotes.
     */
    public function test_quote_multi_tenant_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $clientA = Client::create(['user_id' => $userA->id, 'name' => 'Client A']);
        $clientB = Client::create(['user_id' => $userB->id, 'name' => 'Client B']);

        $quoteA = Quote::create([
            'user_id' => $userA->id,
            'client_id' => $clientA->id,
            'quote_number' => 'ORC-2026-0001',
            'status' => 'draft',
            'issue_date' => '2026-06-01',
            'total_amount' => 100.00,
        ]);

        // User B cannot view User A's quote
        $this->actingAs($userB)->get(route('quotes.show', $quoteA))->assertStatus(403);

        // User B cannot edit User A's quote
        $this->actingAs($userB)->get(route('quotes.edit', $quoteA))->assertStatus(403);

        // User B cannot update User A's quote
        $this->actingAs($userB)->put(route('quotes.update', $quoteA), [
            'client_id' => $clientB->id,
            'status' => 'approved',
            'issue_date' => '2026-06-01',
            'items' => [
                ['description' => 'Serviço do Malicioso', 'quantity' => 1, 'unit_price' => '100,00']
            ]
        ])->assertStatus(403);

        // User B cannot delete User A's quote
        $this->actingAs($userB)->delete(route('quotes.destroy', $quoteA))->assertStatus(403);

        // User B cannot export User A's quote to PDF
        $this->actingAs($userB)->get(route('quotes.pdf', $quoteA))->assertStatus(403);
    }

    /**
     * Test PDF generation.
     */
    public function test_authenticated_user_can_export_quote_pdf(): void
    {
        $user = User::factory()->create();
        $client = Client::create(['user_id' => $user->id, 'name' => 'Client Test']);
        $quote = Quote::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'quote_number' => 'ORC-2026-0001',
            'status' => 'draft',
            'issue_date' => '2026-06-01',
            'total_amount' => 150.00,
        ]);

        QuoteItem::create([
            'quote_id' => $quote->id,
            'description' => 'Serviço de Consultoria',
            'quantity' => 1,
            'unit_price' => 150.00,
            'total_price' => 150.00,
        ]);

        $response = $this->actingAs($user)->get(route('quotes.pdf', $quote));
        
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
