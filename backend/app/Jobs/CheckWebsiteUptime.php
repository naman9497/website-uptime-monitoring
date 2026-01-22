<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\UptimeCheck;
use App\Models\WebsiteStatus;
use App\Services\UptimeCheckService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\Middleware\ThrottlesExceptions;

use Illuminate\Bus\Batchable;

class CheckWebsiteUptime implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Queueable;

    public $timeout = 30;
    public $tries = 2;
    public $uniqueFor = 300;

    public function __construct(
        protected Website $website
    ) {
        //
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->website->id))->releaseAfter(60),
            new ThrottlesExceptions(3, 5),
        ];
    }

    public function uniqueId(): string
    {
        return 'check-website-' . $this->website->id;
    }

    public function handle(UptimeCheckService $service): void
    {
        $result = $service->checkWebsite($this->website);

        $this->storeCheckResult($result);

        $this->updateWebsiteStatus($result);

        if ($result['status'] === 'down' && $this->shouldSendAlert()) {
            SendDowntimeAlert::dispatch($this->website)
                ->onQueue('alerts');
        }
    }

    protected function storeCheckResult(array $result): void
    {
        UptimeCheck::create([
            'website_id' => $this->website->id,
            'status' => $result['status'],
            'response_time_ms' => $result['response_time_ms'],
            'http_status_code' => $result['http_status_code'],
            'error_type' => $result['error_type'],
            'error_message' => $result['error_message'],
            'checked_at' => now(),
        ]);
    }

    protected function updateWebsiteStatus(array $result): void
    {
        $status = WebsiteStatus::firstOrNew(['website_id' => $this->website->id]);

        $previousStatus = $status->current_status ?? 'unknown';
        $newStatus = $result['status'];

        if ($previousStatus !== $newStatus) {
            $status->current_status = $newStatus;

            if ($newStatus === 'down') {
                $status->alert_sent = false;
            }
        }

        $status->last_checked_at = now();
        $status->save();
    }

    protected function shouldSendAlert(): bool
    {
        $status = WebsiteStatus::where('website_id', $this->website->id)->first();

        if ($status && $status->alert_sent) {
            return false;
        }

        return true;
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('CheckWebsiteUptime job failed', [
            'website_id' => $this->website->id,
            'website_url' => $this->website->url,
            'exception' => $exception->getMessage(),
        ]);
    }
}
