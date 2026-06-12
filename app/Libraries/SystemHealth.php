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
            'app' => 'ok',
            'database' => $db,
            'redis' => $redis,
            'request_id' => service('request')->getHeaderLine('X-Request-ID') ?? null,
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
        ];
    }

    private static function dbCheck(): array
    {
        $start = microtime(true);

        try {
            $db = Database::connect();
            $db->query('SELECT 1');

            return [
                'status' => 'ok',
                'ms'     => round((microtime(true) - $start) * 1000, 2),
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'fail',
                'error'  => $e->getMessage(),
                'ms'     => round((microtime(true) - $start) * 1000, 2),
            ];
        }
    }
}