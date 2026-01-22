<?php

namespace App\Console\Commands;

use App\Services\UptimeQueueManager;
use Illuminate\Console\Command;

class StartUptimeWorkers extends Command
{
    protected $signature = 'uptime:workers';

    protected $description = 'Display commands to start all uptime check workers';

    public function handle()
    {
        $this->info('Start these workers (in separate terminals or via Supervisor):');
        $this->newLine();

        foreach (UptimeQueueManager::QUEUES as $queue) {
            $this->line("php artisan queue:work redis --queue={$queue} --sleep=3 --tries=3 --max-time=3600");
        }

        $this->newLine();
        $this->info('Or use Supervisor with numprocs=5 and a single worker listening to all queues:');
        $this->line('php artisan queue:work redis --queue=' . implode(',', UptimeQueueManager::QUEUES) . ' --sleep=3 --tries=3 --max-time=3600');
    }
}
