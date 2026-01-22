<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClientRepository
{
    /**
     * Get all clients with caching
     */
    public function all(): Collection
    {
        return Cache::remember('clients.all', 300, function () {
            return Client::all();
        });
    }

    /**
     * Clear the clients cache
     */
    public function clearCache(): void
    {
        Cache::forget('clients.all');
    }
}
