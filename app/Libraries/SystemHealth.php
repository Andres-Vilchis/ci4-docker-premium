<?php

namespace App\Libraries;

class SystemHealth
{
    public static function check(): array
    {
        $start = microtime(true);

        $db = DatabaseHealth::check();
        $redis = RedisHealth::check();

        return [
            'status' => self::overallStatus($db, $redis),
            'timestamp' => date('c'),
            'memory' => self::memoryUsage(),
            'services' => [
                'database' => $db,
                'redis' => $redis,
            ],
            'latency_ms' => round((microtime(true) - $start) * 1000, 2),
        ];
    }

    private static function memoryUsage(): array
    {
        return [
            'used_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ];
    }

    private static function overallStatus(bool $db, bool $redis): string
    {
        if ($db && $redis) {
            return 'healthy';
        }

        return 'degraded';
    }
}