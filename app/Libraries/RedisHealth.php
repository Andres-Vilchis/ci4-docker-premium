<?php

namespace App\Libraries;

class RedisHealth
{
    public static function check(): bool
    {
        try {
            $redis = new \Redis();

            $host = env('redis.host', 'redis');
            $port = (int) env('redis.port', 6379);

            $redis->connect($host, $port, 1.5);

            $redis->ping();

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Redis health failed: ' . $e->getMessage());
            return false;
        }
    }
}