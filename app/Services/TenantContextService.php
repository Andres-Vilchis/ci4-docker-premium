<?php

namespace App\Services;

use CodeIgniter\Shield\Entities\User;
use RuntimeException;

class TenantContextService
{
    private static ?int $organizationId = null;

    private static ?User $user = null;

    public static function boot(
        int $organizationId,
        ?User $user = null
    ): void {
        self::$organizationId = $organizationId;
        self::$user = $user;
    }

    public static function set(int $organizationId): void
    {
        self::$organizationId = $organizationId;
    }

    public static function get(): ?int
    {
        return self::$organizationId;
    }

    public static function require(): int
    {
        if (self::$organizationId === null) {
            throw new RuntimeException(
                'Tenant not initialized'
            );
        }

        return self::$organizationId;
    }

    public static function organizationId(): int
    {
        return self::require();
    }

    public static function user(): ?User
    {
        return self::$user ?? auth()->user();
    }

    public static function hasTenant(): bool
    {
        return self::$organizationId !== null;
    }

    public static function clear(): void
    {
        self::$organizationId = null;
        self::$user = null;
    }
}