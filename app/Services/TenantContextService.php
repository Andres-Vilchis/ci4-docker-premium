<?php

namespace App\Services;

use CodeIgniter\Shield\Entities\User;
use RuntimeException;

/**
 * Central tenant context holder.
 * Works for both HTTP and CLI environments.
 */
class TenantContextService
{
    private static ?int $organizationId = null;
    private static ?User $user = null;

    public static function boot(int $organizationId, ?User $user = null): void
    {
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
            throw new RuntimeException('Tenant not initialized');
        }

        return self::$organizationId;
    }

    public static function organizationId(): int
    {
        return self::require();
    }

    public static function user(): ?User
    {
        if (self::$user instanceof User) {
            return self::$user;
        }

        if (function_exists('auth')) {
            $auth = auth();
            return $auth ? $auth->user() : null;
        }

        return null;
    }

    /**
     * SAFE for CLI usage.
     * Prevents fatal errors when tenant is not booted.
     */
    public static function safeOrganizationId(): ?int
    {
        return self::$organizationId;
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