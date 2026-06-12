<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Services\DatabaseService;
use App\Services\RedisService;
use App\Observability\ObservabilityService;
use Throwable;

class Health extends ResourceController
{
    public function index()
    {
        $start = microtime(true);

        $request = service('request');
        $requestId = $request->getHeaderLine('X-Request-ID')
            ?: bin2hex(random_bytes(16));

        $dbStatus = ObservabilityService::measure('db_health', fn() =>
            DatabaseService::check()
        );

        $redisStatus = ObservabilityService::measure('redis_health', fn() =>
            RedisService::check()
        );

        $payload = [
            'app' => 'ok',
            'database' => $dbStatus,
            'redis' => $redisStatus,
            'request_id' => $requestId,
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
        ];

        try {
            ObservabilityService::info('health_check', $payload);
        } catch (Throwable $e) {}

        return $this->response
            ->setHeader('X-Request-ID', $requestId)
            ->setJSON($payload);
    }
}