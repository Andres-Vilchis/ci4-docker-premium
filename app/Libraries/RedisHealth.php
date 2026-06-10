<?php

namespace App\Libraries;

use App\Libraries\RedisHealth;
use Config\Database;

class SystemHealth
{
    public static function check(): array
    {
        $start = microtime(true);

        return [
            'status' => self::overallStatus(),
            'timestamp' => date('c'),
            'uptime' => self::uptime(),
            'memory' => self::memoryUsage(),
            'services' => [
                'database' => self::dbCheck(),
                'redis'    => RedisHealth::check(),
            ],
            'latency' => [
                'health_ms' => round((microtime(true) - $start) * 1000, 2),
            ],
        ];
    }

    private static function dbCheck(): bool
    {
        try {
            $db = Database::connect();
            $db->query('SELECT 1');
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'DB health failed: ' . $e->getMessage());
            return false;
        }
    }

    private static function memoryUsage(): array
    {
        return [
            'used_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ];
    }

    private static function uptime(): string
    {
        if (!file_exists('/proc/uptime')) {
            return 'unknown';
        }

        $uptime = (float) explode(' ', file_get_contents('/proc/uptime'))[0];

        $hours = floor($uptime / 3600);
        $minutes = floor(($uptime % 3600) / 60);

        return "{$hours}h {$minutes}m";
    }

    private static function overallStatus(): string
    {
        $db = self::dbCheck();
        $redis = RedisHealth::check();

        if ($db && $redis) {
            return 'healthy';
        }

        return 'degraded';
    }
}