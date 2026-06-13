<?php

namespace App\Services;

class TenantSessionService
{
    private const SESSION_KEY = 'active_organization_id';

    public static function set(int $organizationId): void
    {
        session()->set(self::SESSION_KEY, $organizationId);
    }

    public static function get(): ?int
    {
        return session()->get(self::SESSION_KEY);
    }

    public static function clear(): void
    {
        session()->remove(self::SESSION_KEY);
    }

    public static function hasTenant(): bool
    {
        return self::get() !== null;
    }
}