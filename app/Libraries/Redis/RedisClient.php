<?php

namespace App\Libraries\Redis;

use Redis;
use Throwable;

class RedisClient
{
    private static ?Redis $instance = null;

    public static function connection(): Redis
    {
        if (self::$instance instanceof Redis) {
            return self::$instance;
        }

        $redis = new Redis();

        $host = env('cache.redis.host', 'redis');
        $port = (int) env('cache.redis.port', 6379);
        $timeout = 2.0;

        try {
            $redis->connect($host, $port, $timeout);
            self::$instance = $redis;

            return $redis;
        } catch (Throwable $e) {
            log_message('error', 'Redis connection failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function ping(): bool
    {
        try {
            return self::connection()->ping() === '+PONG' || true;
        } catch (Throwable $e) {
            return false;
        }
    }
}