<?php

namespace App\Libraries\Redis;

use Redis;
use Throwable;

class RedisClient
{
    private static ?Redis $instance = null;

    private static float $timeout = 2.0;

    /**
     * Get singleton Redis connection
     */
    public static function connection(): Redis
    {
        if (self::$instance instanceof Redis) {
            return self::$instance;
        }

        $host = env('cache.redis.host', 'redis');
        $port = (int) env('cache.redis.port', 6379);
        $timeout = (float) env('cache.redis.timeout', self::$timeout);

        $redis = new Redis();

        try {
            $connected = $redis->connect($host, $port, $timeout);

            if (!$connected) {
                throw new \RuntimeException("Redis connection failed to {$host}:{$port}");
            }

            // Optional auth support (future-proof)
            $password = env('cache.redis.password');
            if (!empty($password)) {
                $redis->auth($password);
            }

            // Select DB if needed
            $db = (int) env('cache.redis.database', 0);
            if ($db > 0) {
                $redis->select($db);
            }

            self::$instance = $redis;

            return $redis;

        } catch (Throwable $e) {
            log_message('error', 'Redis connection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function ping(): bool
    {
        try {
            $redis = self::connection();

            $response = $redis->ping();

            if ($response === true) {
                return true;
            }

            if (is_string($response)) {
                return strtoupper($response) === 'PONG' || strtoupper($response) === '+PONG';
            }

            return false;

        } catch (Throwable $e) {
            log_message('error', 'Redis ping failed: ' . $e->getMessage());
            return false;
        }
    }

    public static function health(): array
    {
        $start = microtime(true);

        try {
            $ok = self::ping();

            return [
                'status' => $ok ? 'ok' : 'fail',
                'ms' => round((microtime(true) - $start) * 1000, 2),
            ];

        } catch (Throwable $e) {
            return [
                'status' => 'fail',
                'error' => $e->getMessage(),
                'ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        }
    }
}