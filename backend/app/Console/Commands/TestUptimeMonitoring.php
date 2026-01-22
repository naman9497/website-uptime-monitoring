<?php

namespace App\Console\Commands;

use App\Jobs\CheckWebsiteUptime;
use App\Models\Website;
use Illuminate\Console\Command;

class TestUptimeMonitoring extends Command
{
    protected $signature = 'uptime:test';

    protected $description = 'Test uptime monitoring for all websites';

    public function handle()
    {
        $websites = Website::all();

        $this->info("Dispatching uptime checks for {$websites->count()} websites...");

        foreach ($websites as $website) {
            CheckWebsiteUptime::dispatch($website)->onQueue('uptime-checks');
            $this->info("✓ Dispatched check for: {$website->url}");
        }

        $this->info("\nJobs dispatched! Run 'sail artisan queue:work' to process them.");

        return 0;
    }
}
