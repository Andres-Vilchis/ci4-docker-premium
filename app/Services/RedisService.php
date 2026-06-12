<?php

namespace App\Services;

use Config\Services;
use Throwable;

class RedisService
{
    public static function check(): array
    {
        $start = microtime(true);

        try {
            $cache = Services::cache();
            $handler = $cache->getHandler();

            if (method_exists($handler, 'ping')) {
                $handler->ping();
            } else {
                $cache->save('__healthcheck', 'ok', 10);
                $cache->get('__healthcheck');
            }

            return [
                'status' => 'ok',
                'ms' => (microtime(true) - $start) * 1000,
            ];
        } catch (Throwable $e) {
            return [
                'status' => 'fail',
                'ms' => (microtime(true) - $start) * 1000,
                'error' => $e->getMessage(),
            ];
        }
    }
}
