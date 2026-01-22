<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_websites_for_client(): void
    {
        $client = Client::factory()->create();
        $websites = Website::factory()->count(3)->for($client)->create();

        $response = $this->getJson(route('clients.websites.index', $client));

        $response->assertOk()
            ->assertJsonCount(3, 'data');

        foreach ($websites as $website) {
            $response->assertJsonFragment([
                'uuid' => $website->uuid,
                'url' => $website->url,
            ]);
        }
    }

    public function test_index_returns_empty_array_when_client_has_no_websites(): void
    {
        $client = Client::factory()->create();

        $response = $this->getJson(route('clients.websites.index', $client));

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_index_returns_404_for_non_existent_client(): void
    {
        $fakeUuid = '00000000-0000-0000-0000-000000000000';

        $response = $this->getJson(route('clients.websites.index', ['client' => $fakeUuid]));

        $response->assertNotFound();
    }

    public function test_index_returns_correct_resource_structure(): void
    {
        $client = Client::factory()->create();
        Website::factory()->for($client)->create();

        $response = $this->getJson(route('clients.websites.index', $client));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['uuid', 'url'],
                ],
            ]);
    }

    public function test_index_includes_current_status_when_available(): void
    {
        $client = Client::factory()->create();
        $website = Website::factory()->for($client)->create();
        WebsiteStatus::factory()->for($website)->up()->create();

        $response = $this->getJson(route('clients.websites.index', $client));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['uuid', 'url', 'current_status'],
                ],
            ]);

        $this->assertEquals('up', $response->json('data.0.current_status.current_status'));
    }

    public function test_index_only_returns_websites_belonging_to_specified_client(): void
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        $websitesClient1 = Website::factory()->count(2)->for($client1)->create();
        Website::factory()->count(3)->for($client2)->create();

        $response = $this->getJson(route('clients.websites.index', $client1));

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        foreach ($websitesClient1 as $website) {
            $response->assertJsonFragment([
                'uuid' => $website->uuid,
            ]);
        }
    }
}
