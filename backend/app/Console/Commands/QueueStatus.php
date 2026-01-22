<?php

namespace App\Console\Commands;

use App\Services\UptimeQueueManager;
use Illuminate\Console\Command;

class QueueStatus extends Command
{
    protected $signature = 'uptime:queue-status';

    protected $description = 'Show the current size of all uptime check queues';

    public function handle(UptimeQueueManager $queueManager)
    {
        $sizes = $queueManager->getAllQueueSizes();
        $total = array_sum($sizes);

        $this->table(
            ['Queue', 'Jobs'],
            collect($sizes)->map(fn ($size, $queue) => [$queue, $size])->toArray()
        );

        $this->info("Total: {$total} jobs");
    }
}
