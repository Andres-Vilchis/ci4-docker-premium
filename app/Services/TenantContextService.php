<?php

namespace App\Services;

class TenantContextService
{
    private static ?int $organizationId = null;

    public static function set(int $organizationId): void
    {
        self::$organizationId = $organizationId;
        session()->set('active_organization_id', $organizationId);
    }

    public static function get(): ?int
    {
        if (self::$organizationId !== null) {
            return self::$organizationId;
        }

        return session()->get('active_organization_id');
    }

    public static function require(): int
    {
        $id = self::get();

        if (!$id) {
            throw new \RuntimeException('No active organization selected');
        }

        return $id;
    }

    public static function clear(): void
    {
        self::$organizationId = null;
        session()->remove('active_organization_id');
    }
}