<?php

namespace App\Libraries;

use Redis;

class RedisClient
{
    private static ?Redis $instance = null;

    public static function conn(): Redis
    {
        if (self::$instance) {
            return self::$instance;
        }

        $redis = new Redis();

        $host = env('cache.redis.host', 'redis');
        $port = (int) env('cache.redis.port', 6379);

        $redis->connect($host, $port, 1.5);

        self::$instance = $redis;

        return $redis;
    }
}