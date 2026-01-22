<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_array_when_no_clients_exist(): void
    {
        $response = $this->getJson(route('clients.index'));

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_index_returns_all_clients(): void
    {
        $clients = Client::factory()->count(3)->create();

        $response = $this->getJson(route('clients.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data');

        foreach ($clients as $client) {
            $response->assertJsonFragment([
                'uuid' => $client->uuid,
                'email' => $client->email,
            ]);
        }
    }

    public function test_index_returns_correct_resource_structure(): void
    {
        Client::factory()->create();

        $response = $this->getJson(route('clients.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['uuid', 'email'],
                ],
            ]);
    }
}
