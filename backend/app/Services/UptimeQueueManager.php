<?php

namespace App\Services;

use Illuminate\Support\Facades\Queue;

class UptimeQueueManager
{
    public function getQueues(): array
    {
        $numWorkers = config('queue.uptime_workers', 5);
        $queues = [];

        for ($i = 1; $i <= $numWorkers; $i++) {
            $queues[] = "uptime-checks-{$i}";
        }

        return $queues;
    }

    public function getLeastLoadedQueue(): string
    {
        $queueSizes = [];

        foreach ($this->getQueues() as $queue) {
            $queueSizes[$queue] = $this->getQueueSize($queue);
        }

        return array_keys($queueSizes, min($queueSizes))[0];
    }

    public function getQueueSize(string $queue): int
    {
        return Queue::connection()->size($queue);
    }

    public function getAllQueueSizes(): array
    {
        $sizes = [];

        foreach ($this->getQueues() as $queue) {
            $sizes[$queue] = $this->getQueueSize($queue);
        }

        return $sizes;
    }
}
