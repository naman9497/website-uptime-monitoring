<?php

namespace App\Console\Commands;

use App\Jobs\CheckWebsiteUptime;
use App\Models\Website;
use App\Services\UptimeQueueManager;
use Illuminate\Console\Command;

class CheckUptimeCommand extends Command
{
    protected $signature = 'uptime:check';

    protected $description = 'Dispatch uptime check jobs for all websites';

    public function handle(UptimeQueueManager $queueManager)
    {
        $count = 0;

        Website::lazy(100)->each(function (Website $website) use (&$count, $queueManager) {
            $queue = $queueManager->getLeastLoadedQueue();

            CheckWebsiteUptime::dispatch($website)
                ->onQueue($queue);
            $count++;
        });

        $this->info("Dispatched {$count} uptime check jobs.");

        foreach ($queueManager->getAllQueueSizes() as $queue => $size) {
            $this->line("  {$queue}: {$size} jobs");
        }
    }
}
