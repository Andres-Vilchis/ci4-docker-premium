<?php

namespace App\Services;

class TenantSessionService
{
    private const SESSION_KEY = 'active_organization_id';

    private static function isCli(): bool
    {
        return defined('IS_CLI_MODE') && IS_CLI_MODE;
    }

    public static function set(int $organizationId): void
    {
        if (self::isCli()) {
            return;
        }

        session()->set(self::SESSION_KEY, $organizationId);
    }

    public static function get(): ?int
    {
        if (self::isCli()) {
            return null;
        }

        return session()->get(self::SESSION_KEY);
    }

    public static function clear(): void
    {
        if (self::isCli()) {
            return;
        }

        session()->remove(self::SESSION_KEY);
    }

    public static function hasTenant(): bool
    {
        return self::get() !== null;
    }
}