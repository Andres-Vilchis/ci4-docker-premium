<?php

namespace App\Services;

use App\Libraries\Redis\RedisClient;
use Throwable;

class RedisService
{
    public static function check(): array
    {
        $start = microtime(true);

        try {
            $redis = RedisClient::connection();

            $pong = $redis->ping();

            if ($pong === false || $pong === null) {
                throw new \Exception('Redis ping failed');
            }

            return [
                'status' => 'ok',
                'ms' => round((microtime(true) - $start) * 1000, 2),
            ];

        } catch (Throwable $e) {

            return [
                'status' => 'fail',
                'ms' => round((microtime(true) - $start) * 1000, 2),
                'error' => $e->getMessage(),
            ];
        }
    }
}