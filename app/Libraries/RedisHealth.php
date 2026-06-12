<?php

namespace App\Libraries;

class RedisHealth
{
    public static function check(): array
    {
        $start = microtime(true);

        try {
            $redis = RedisClient::conn();

            $pong = $redis->ping();

            return [
                'status' => $pong ? 'ok' : 'fail',
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