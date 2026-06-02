<?php

namespace Tests\Feature;

use App\Mail\QuoteMail;
use App\Models\Client;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    // =====================================================
    // TESTES DE ENVIO POR E-MAIL
    // =====================================================


    /**
     * Helper: cria um quote completo com item para uso nos testes de e-mail.
     */
    private function makeQuoteWithItem(User $user): Quote
    {
        $client = Client::create([
            'user_id' => $user->id,
            'name'    => 'Cliente E-mail Teste',
            'email'   => 'cliente@emailteste.com',
        ]);

        $quote = Quote::create([
            'user_id'      => $user->id,
            'client_id'    => $client->id,
            'quote_number' => 'ORC-2026-EMAIL',
            'status'       => 'draft',
            'issue_date'   => '2026-06-01',
            'total_amount' => 500.00,
        ]);

        QuoteItem::create([
            'quote_id'    => $quote->id,
            'description' => 'Desenvolvimento Web',
            'quantity'    => 2,
            'unit_price'  => 250.00,
            'total_price' => 500.00,
        ]);

        return $quote;
    }

    /**
     * Envio de e-mail bem-sucedido: Mailable disparado com destinatário correto.
     */
    public function test_authenticated_user_can_send_quote_by_email(): void
    {
        Mail::fake();

        $user  = User::factory()->create();
        $quote = $this->makeQuoteWithItem($user);

        $response = $this->actingAs($user)->post(route('quotes.sendEmail', $quote), [
            'recipient_email' => 'cliente@emailteste.com',
            'custom_message'  => 'Segue a proposta conforme combinado.',
        ]);

        $response->assertRedirect(route('quotes.show', $quote));
        $response->assertSessionHas('success');

        // Verifica que o Mailable foi enviado para o endereço correto
        Mail::assertSent(QuoteMail::class, function (QuoteMail $mail) use ($quote) {
            return $mail->quote->id === $quote->id
                && $mail->customMessage === 'Segue a proposta conforme combinado.'
                && $mail->hasTo('cliente@emailteste.com');
        });
    }

    /**
     * Status deve mudar automaticamente de 'draft' para 'sent' após o envio.
     */
    public function test_quote_status_changes_to_sent_after_email(): void
    {
        Mail::fake();

        $user  = User::factory()->create();
        $quote = $this->makeQuoteWithItem($user);

        $this->assertEquals('draft', $quote->status);

        $this->actingAs($user)->post(route('quotes.sendEmail', $quote), [
            'recipient_email' => 'destino@qualquer.com',
        ]);

        $this->assertDatabaseHas('quotes', [
            'id'     => $quote->id,
            'status' => 'sent',
        ]);
    }

    /**
     * Status 'approved' não deve ser rebaixado para 'sent'.
     */
    public function test_approved_quote_status_not_changed_after_email(): void
    {
        Mail::fake();

        $user  = User::factory()->create();
        $quote = $this->makeQuoteWithItem($user);
        $quote->update(['status' => 'approved']);

        $this->actingAs($user)->post(route('quotes.sendEmail', $quote), [
            'recipient_email' => 'destino@qualquer.com',
        ]);

        $this->assertDatabaseHas('quotes', [
            'id'     => $quote->id,
            'status' => 'approved', // deve permanecer approved
        ]);
    }

    /**
     * Usuário B não pode enviar e-mail do orçamento do Usuário A (isolamento multi-tenant).
     */
    public function test_other_user_cannot_send_email_for_quote(): void
    {
        Mail::fake();

        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $quote = $this->makeQuoteWithItem($userA);

        $response = $this->actingAs($userB)->post(route('quotes.sendEmail', $quote), [
            'recipient_email' => 'ataque@hacker.com',
        ]);

        $response->assertStatus(403);
        Mail::assertNothingSent();
    }

    /**
     * Validação: e-mail inválido deve retornar erro de validação.
     */
    public function test_send_email_validates_recipient_email(): void
    {
        Mail::fake();

        $user  = User::factory()->create();
        $quote = $this->makeQuoteWithItem($user);

        $response = $this->actingAs($user)->post(route('quotes.sendEmail', $quote), [
            'recipient_email' => 'nao-e-um-email-valido',
        ]);

        $response->assertSessionHasErrors(['recipient_email']);
        Mail::assertNothingSent();
    }

    /**
     * Visitante não pode usar a rota de envio de e-mail.
     */
    public function test_guest_cannot_send_quote_email(): void
    {
        $userA = User::factory()->create();
        $quote = $this->makeQuoteWithItem($userA);

        $this->post(route('quotes.sendEmail', $quote), [
            'recipient_email' => 'alguem@teste.com',
        ])->assertRedirect(route('login'));
    }
}
