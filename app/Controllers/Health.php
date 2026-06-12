<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use Config\Services;
use Throwable;

class Health extends ResourceController
{
    public function index()
    {
        $start = microtime(true);

        $logger = Services::logger();

        $request = service('request');
        $requestId = $request->getHeaderLine('X-Request-ID') ?: bin2hex(random_bytes(16));

        $dbStatus = $this->checkDatabase();
        $redisStatus = $this->checkRedis();

        $payload = [
            'app' => 'ok',
            'database' => $dbStatus,
            'redis' => $redisStatus,
            'request_id' => $requestId,
            'total_ms' => (microtime(true) - $start) * 1000,
        ];

        try {
            $logger->info('health_check', [
                'request_id' => $requestId,
                'context' => $payload,
            ]);
        } catch (Throwable $e) {
            // logging must never break health endpoint
        }

        return $this->response
            ->setHeader('X-Request-ID', $requestId)
            ->setJSON($payload);
    }

    private function checkDatabase(): array
    {
        $start = microtime(true);

        try {
            $db = Database::connect();
            $db->query('SELECT 1');

            return [
                'status' => 'ok',
                'ms' => (microtime(true) - $start) * 1000,
            ];
        } catch (Throwable $e) {
            return [
                'status' => 'fail',
                'error' => $e->getMessage(),
                'ms' => (microtime(true) - $start) * 1000,
            ];
        }
    }

    private function checkRedis(): array
    {
        $start = microtime(true);

        try {
            if (!class_exists(\Redis::class)) {
                throw new \Exception('Redis extension not installed');
            }

            $redis = new \Redis();

            $connected = $redis->connect(
                env('cache.redis.host', 'redis'),
                (int) env('cache.redis.port', 6379),
                2.0
            );

            if (!$connected) {
                throw new \Exception('Cannot connect to Redis');
            }

            $redis->ping();

            return [
                'status' => 'ok',
                'ms' => (microtime(true) - $start) * 1000,
            ];
        } catch (Throwable $e) {
            return [
                'status' => 'fail',
                'error' => $e->getMessage(),
                'ms' => (microtime(true) - $start) * 1000,
            ];
        }
    }
}