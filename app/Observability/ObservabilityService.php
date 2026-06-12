<?php

namespace App\Observability;

use App\Observability\Context\RequestContext;

class ObservabilityService
{
    public static function log(string $level, string $message, array $context = []): void
    {
        $payload = [
            'level'     => $level,
            'message'   => $message,
            'timestamp' => date('c'),
            'request'   => RequestContext::all(),
            'context'   => $context,
        ];

        log_message($level, json_encode($payload, JSON_UNESCAPED_UNICODE));
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::log('debug', $message, $context);
    }

    public static function measure(string $name, callable $fn): mixed
    {
        $start = microtime(true);

        try {
            $result = $fn();

            self::info("metric.$name.ok", [
                'duration_ms' => (microtime(true) - $start) * 1000
            ]);

            return $result;

        } catch (\Throwable $e) {

            self::error("metric.$name.fail", [
                'error' => $e->getMessage(),
                'duration_ms' => (microtime(true) - $start) * 1000
            ]);

            throw $e;
        }
    }
}