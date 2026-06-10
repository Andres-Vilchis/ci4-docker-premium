<?php

namespace App\Libraries\Queue;

use Redis;

class QueueMonitorService
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(
            env('cache.redis.host', 'redis'),
            (int) env('cache.redis.port', 6379)
        );
    }

    public function stats(): array
    {
        return [
            'pending' => $this->count('queue:pending'),
            'failed'  => $this->count('queue:failed'),
            'processed' => $this->count('queue:processed'),
        ];
    }

    public function failedJobs(): array
    {
        return $this->redis->lRange('queue:failed', 0, -1);
    }

    public function retryJob(string $payload): bool
    {
        try {
            $this->redis->rPush('queue:pending', $payload);
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Retry failed: ' . $e->getMessage());
            return false;
        }
    }

    private function count(string $key): int
    {
        try {
            return $this->redis->lLen($key);
        } catch (\Throwable $e) {
            return 0;
        }
    }
}