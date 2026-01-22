<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\WebsiteStatus;
use App\Mail\WebsiteDownAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDowntimeAlert implements ShouldQueue
{
    use Queueable;

    public $timeout = 60;
    public $tries = 3;
    public $backoff = [10, 30, 60];

    public function __construct(
        public Website $website
    ) {
        $this->onQueue('alerts');
    }

    public function handle(): void
    {
        $this->website->load('client');

        $status = WebsiteStatus::where('website_id', $this->website->id)->first();

        if ($status && $status->alert_sent) {
            Log::info('Alert skipped: already sent', [
                'website_id' => $this->website->id,
            ]);
            return;
        }

        Mail::to($this->website->client->email)
            ->send(new WebsiteDownAlert($this->website));

        if ($status) {
            $status->update([
                'alert_sent' => true,
                'alert_sent_at' => now(),
            ]);
        }

        Log::info('Downtime alert sent', [
            'website_id' => $this->website->id,
            'website_url' => $this->website->url,
            'client_email' => $this->website->client->email,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Failed to send downtime alert', [
            'website_id' => $this->website->id,
            'website_url' => $this->website->url,
            'exception' => $exception->getMessage(),
        ]);
    }
}
