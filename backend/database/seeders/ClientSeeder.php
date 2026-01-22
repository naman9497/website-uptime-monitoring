<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Website;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->has(Website::factory()->count(10))->count(100)->create();
    }
}
