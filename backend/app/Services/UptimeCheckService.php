<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

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

            if ($response->successful()) {
                return [
                    'status' => 'up',
                    'response_time_ms' => $responseTime,
                    'http_status_code' => $response->status(),
                    'error_type' => null,
                    'error_message' => null,
                ];
            } else {
                return [
                    'status' => 'down',
                    'response_time_ms' => $responseTime,
                    'http_status_code' => $response->status(),
                    'error_type' => 'http_error',
                    'error_message' => "HTTP {$response->status()}: " . $response->reason(),
                ];
            }

        } catch (ConnectionException $e) {
            return [
                'status' => 'down',
                'response_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
                'http_status_code' => null,
                'error_type' => $this->determineConnectionErrorType($e),
                'error_message' => $e->getMessage(),
            ];
        } catch (RequestException $e) {
            return [
                'status' => 'down',
                'response_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
                'http_status_code' => null,
                'error_type' => 'timeout',
                'error_message' => 'Request timeout after ' . $timeout . ' seconds',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'down',
                'response_time_ms' => null,
                'http_status_code' => null,
                'error_type' => 'unknown',
                'error_message' => $e->getMessage(),
            ];
        }
    }

    protected function determineConnectionErrorType(\Exception $e): string
    {
        $message = strtolower($e->getMessage());

        if (str_contains($message, 'dns') || str_contains($message, 'resolve')) {
            return 'dns';
        }
        if (str_contains($message, 'ssl') || str_contains($message, 'certificate')) {
            return 'ssl';
        }
        if (str_contains($message, 'connection refused') || str_contains($message, 'unreachable')) {
            return 'connection';
        }

        return 'network';
    }
}
