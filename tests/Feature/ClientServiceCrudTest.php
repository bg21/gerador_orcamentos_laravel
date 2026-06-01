<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientServiceCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest access is restricted.
     */
    public function test_guests_cannot_access_clients_or_services(): void
    {
        $this->get(route('clients.index'))->assertRedirect(route('login'));
        $this->get(route('services.index'))->assertRedirect(route('login'));
    }

    /**
     * Test authenticated user can perform Client CRUD.
     */
    public function test_authenticated_user_can_perform_client_crud(): void
    {
        $user = User::factory()->create();

        // 1. Create client
        $response = $this->actingAs($user)->post(route('clients.store'), [
            'name' => 'Cliente Teste',
            'document' => '12345678900',
            'email' => 'cliente@teste.com',
            'phone' => '11999999999',
            'address' => 'Rua Teste, 123',
        ]);

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
        ]);

        $client = Client::first();

        // 2. View edit page
        $this->actingAs($user)->get(route('clients.edit', $client))->assertOk();

        // 3. Update client
        $response = $this->actingAs($user)->put(route('clients.update', $client), [
            'name' => 'Cliente Alterado',
            'document' => '12345678900',
            'email' => 'cliente@alterado.com',
            'phone' => '11999999999',
            'address' => 'Rua Alterada, 321',
        ]);

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Cliente Alterado',
            'email' => 'cliente@alterado.com',
        ]);

        // 4. Delete client
        $response = $this->actingAs($user)->delete(route('clients.destroy', $client));
        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseMissing('clients', [
            'id' => $client->id,
        ]);
    }

    /**
     * Test multi-tenant isolation for clients.
     */
    public function test_client_multi_tenant_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $clientA = Client::create([
            'user_id' => $userA->id,
            'name' => 'Cliente do User A',
        ]);

        // User B cannot edit User A's client
        $this->actingAs($userB)->get(route('clients.edit', $clientA))->assertStatus(403);

        // User B cannot update User A's client
        $this->actingAs($userB)->put(route('clients.update', $clientA), [
            'name' => 'Tentativa de alteração',
        ])->assertStatus(403);

        // User B cannot delete User A's client
        $this->actingAs($userB)->delete(route('clients.destroy', $clientA))->assertStatus(403);
    }

    /**
     * Test authenticated user can perform Service CRUD.
     */
    public function test_authenticated_user_can_perform_service_crud(): void
    {
        $user = User::factory()->create();

        // 1. Create service (simulating BRL price input: '1.250,50')
        $response = $this->actingAs($user)->post(route('services.store'), [
            'name' => 'Serviço de Programação',
            'description' => 'Desenvolvimento de backend em Laravel',
            'default_price' => '1.250,50',
        ]);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', [
            'user_id' => $user->id,
            'name' => 'Serviço de Programação',
            'default_price' => 1250.50,
        ]);

        $service = Service::first();

        // 2. View edit page
        $this->actingAs($user)->get(route('services.edit', $service))->assertOk();

        // 3. Update service
        $response = $this->actingAs($user)->put(route('services.update', $service), [
            'name' => 'Serviço de Backend Alterado',
            'description' => 'Escopo alterado',
            'default_price' => '150,00',
        ]);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Serviço de Backend Alterado',
            'default_price' => 150.00,
        ]);

        // 4. Delete service
        $response = $this->actingAs($user)->delete(route('services.destroy', $service));
        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseMissing('services', [
            'id' => $service->id,
        ]);
    }

    /**
     * Test multi-tenant isolation for services.
     */
    public function test_service_multi_tenant_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $serviceA = Service::create([
            'user_id' => $userA->id,
            'name' => 'Serviço do User A',
            'default_price' => 100.00,
        ]);

        // User B cannot edit User A's service
        $this->actingAs($userB)->get(route('services.edit', $serviceA))->assertStatus(403);

        // User B cannot update User A's service
        $this->actingAs($userB)->put(route('services.update', $serviceA), [
            'name' => 'Tentativa de alteração',
            'default_price' => 200.00,
        ])->assertStatus(403);

        // User B cannot delete User A's service
        $this->actingAs($userB)->delete(route('services.destroy', $serviceA))->assertStatus(403);
    }
}
