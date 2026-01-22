<?php

namespace App\Console\Commands;

use App\Jobs\CheckWebsiteUptime;
use App\Models\Website;
use App\Services\UptimeQueueManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class DispatchUptimeChecks extends Command
{
    protected $signature = 'uptime:check-all';

    protected $description = 'Dispatch uptime checks for all websites in a batch';

    public function handle(UptimeQueueManager $queueManager)
    {
        $this->info('Fetching websites...');

        $websites = Website::all();

        if ($websites->isEmpty()) {
            $this->info('No websites found to check.');
            return;
        }

        $jobs = [];
        foreach ($websites as $website) {
            $queue = $queueManager->getLeastLoadedQueue();
            $jobs[] = (new CheckWebsiteUptime($website))->onQueue($queue);
        }

        $this->info('Dispatching batch for ' . $websites->count() . ' websites...');

        try {
            $batch = Bus::batch($jobs)
                ->name('Uptime Checks ' . now()->toDateTimeString())
                ->then(function ($batch) {
                    Log::info('Uptime check batch finished successfully.', ['batch_id' => $batch->id]);
                })
                ->catch(function ($batch, Throwable $e) {
                    Log::error('Uptime check batch failed.', ['batch_id' => $batch->id, 'error' => $e->getMessage()]);
                })
                ->finally(function ($batch) {
                    Log::info('Uptime check batch completed.', ['batch_id' => $batch->id]);
                })
                ->dispatch();

            $this->info('Batch dispatched successfully. Batch ID: ' . $batch->id);

            foreach ($queueManager->getAllQueueSizes() as $queue => $size) {
                $this->line("  {$queue}: {$size} jobs");
            }
        } catch (Throwable $e) {
            $this->error('Failed to dispatch batch: ' . $e->getMessage());
            Log::error('Failed to dispatch uptime check batch.', ['error' => $e->getMessage()]);
        }
    }
}
