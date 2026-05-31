<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTradeData;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use CreatesTradeData;
    use RefreshDatabase;

    public function test_guests_are_redirected_from_clients_index(): void
    {
        $this->get(route('clients.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_clients_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('clients.index'))
            ->assertOk()
            ->assertSee('Clients');
    }

    public function test_clients_index_only_shows_own_clients(): void
    {
        ['user' => $user, 'client' => $ownClient] = $this->createUserWithClient();
        Client::factory()->create(['name' => 'Someone Else']);

        $this->actingAs($user)
            ->get(route('clients.index'))
            ->assertOk()
            ->assertSee($ownClient->name)
            ->assertDontSee('Someone Else');
    }

    public function test_create_client_route_opens_modal_on_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('clients.create'))
            ->assertRedirect(route('clients.index', ['open' => 'create-client']));

        $this->actingAs($user)
            ->get(route('clients.index', ['open' => 'create-client']))
            ->assertOk()
            ->assertSee('New client');
    }

    public function test_user_can_create_a_client(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('clients.store'), [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'phone' => '07700900123',
                'address' => '1 High Street',
                'notes' => 'Regular customer',
            ])
            ->assertRedirect(route('clients.index'))
            ->assertSessionHas('status', 'client-created');

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name' => 'John Smith',
            'email' => 'john@example.com',
        ]);
    }

    public function test_client_name_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('clients.index'))
            ->post(route('clients.store'), ['name' => ''])
            ->assertRedirect(route('clients.index'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('clients', 0);
    }

    public function test_user_can_view_their_client(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->get(route('clients.show', $client))
            ->assertOk()
            ->assertSee($client->name);
    }

    public function test_user_cannot_view_another_users_client(): void
    {
        ['client' => $client] = $this->createUserWithClient();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('clients.show', $client))
            ->assertForbidden();
    }

    public function test_user_can_edit_their_client(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->get(route('clients.edit', $client))
            ->assertOk()
            ->assertSee('Edit Client');
    }

    public function test_user_can_update_their_client(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->put(route('clients.update', $client), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => null,
                'address' => null,
                'notes' => null,
            ])
            ->assertRedirect(route('clients.show', $client))
            ->assertSessionHas('status', 'client-updated');

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_user_cannot_update_another_users_client(): void
    {
        ['client' => $client] = $this->createUserWithClient();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->put(route('clients.update', $client), [
                'name' => 'Hacked',
                'email' => null,
                'phone' => null,
                'address' => null,
                'notes' => null,
            ])
            ->assertForbidden();
    }

    public function test_user_can_delete_their_client(): void
    {
        ['user' => $user, 'client' => $client] = $this->createUserWithClient();

        $this->actingAs($user)
            ->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.index'))
            ->assertSessionHas('status', 'client-deleted');

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    public function test_user_cannot_delete_another_users_client(): void
    {
        ['client' => $client] = $this->createUserWithClient();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->delete(route('clients.destroy', $client))
            ->assertForbidden();

        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }
}
