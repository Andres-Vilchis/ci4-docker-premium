<?php

namespace App\Libraries;

use Config\Database;

class SystemHealth
{
    public static function check(): array
    {
        $start = microtime(true);

        $db = self::dbCheck();
        $redis = RedisHealth::check();

        return [
            'status'    => self::overallStatus($db, $redis),
            'timestamp' => date('c'),
            'uptime'    => self::uptime(),
            'memory'    => self::memoryUsage(),
            'services'  => [
                'database' => $db,
                'redis'    => $redis,
            ],
            'latency_ms' => round((microtime(true) - $start) * 1000, 2),
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

        $hours = (int) floor($uptime / 3600);
        $minutes = (int) floor(($uptime % 3600) / 60);

        return "{$hours}h {$minutes}m";
    }

    private static function overallStatus(bool $db, bool $redis): string
    {
        return ($db && $redis) ? 'healthy' : 'degraded';
    }
}