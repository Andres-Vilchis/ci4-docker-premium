<?php

namespace App\Libraries;

use App\Libraries\Redis\RedisClient;

class RedisHealth
{
    public static function check(): array
    {
        $start = microtime(true);

        try {
            $redis = RedisClient::connection();

            $pong = $redis->ping();

            $ok = $pong !== false && $pong !== null;

            return [
                'status' => $ok ? 'ok' : 'fail',
                'ms' => round((microtime(true) - $start) * 1000, 2),
            ];

        } catch (\Throwable $e) {
            return [
                'status' => 'fail',
                'error' => $e->getMessage(),
                'ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        }
    }
}