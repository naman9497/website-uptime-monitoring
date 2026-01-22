<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Website;
use Illuminate\Database\Seeder;

class ClientWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $client1 = Client::create([
            'email' => 'client1@example.com',
        ]);

        Website::create([
            'client_id' => $client1->id,
            'url' => 'https://google.com',
        ]);

        Website::create([
            'client_id' => $client1->id,
            'url' => 'https://example.com',
        ]);

        Website::create([
            'client_id' => $client1->id,
            'url' => 'https://httpstat.us/404',
        ]);

        $client2 = Client::create([
            'email' => 'client2@example.com',
        ]);

        Website::create([
            'client_id' => $client2->id,
            'url' => 'https://github.com',
        ]);

        Website::create([
            'client_id' => $client2->id,
            'url' => 'https://laravel.com',
        ]);
    }
}
