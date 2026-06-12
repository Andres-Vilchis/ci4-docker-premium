<?php

namespace App\Observability\Context;

class RequestContext
{
    public static string $requestId;
    public static float $startTime;

    public static ?int $tenantId = null;
    public static ?int $userId = null;

    public static array $meta = [];

    public static function init(): void
    {
        self::$requestId = bin2hex(random_bytes(16));
        self::$startTime = microtime(true);
    }

    public static function elapsedMs(): float
    {
        return (microtime(true) - self::$startTime) * 1000;
    }

    public static function set(string $key, mixed $value): void
    {
        self::$meta[$key] = $value;
    }

    public static function all(): array
    {
        return [
            'request_id' => self::$requestId ?? null,
            'tenant_id'  => self::$tenantId,
            'user_id'    => self::$userId,
            'meta'       => self::$meta,
        ];
    }
}