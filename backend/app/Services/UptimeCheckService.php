<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class UptimeCheckService
{
    public function checkWebsite(Website $website): array
    {
        $startTime = microtime(true);
        $timeout = 10;

        try {
            $response = Http::timeout($timeout)
                ->withOptions([
                    'verify' => true,
                    'allow_redirects' => true,
                ])
                ->get($website->url);

            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            return ['status' => $response->successful() ? 'up' : 'down'];

        } catch (ConnectionException|\Exception $e) {
            return ['status' => 'down'];
        }
    }
}
