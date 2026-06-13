<?php

namespace App\Services;

trigger_error(
    'TenantContextService is deprecated. Use service("tenantContext")',
    E_USER_DEPRECATED
);

class TenantContextService
{
    public static function set(int $tenantId): void
    {
        service('tenantContext')->setTenantId($tenantId);
    }

    public static function get(): ?int
    {
        return service('tenantContext')->tenantId();
    }

    public static function hasTenant(): bool
    {
        return service('tenantContext')->hasTenant();
    }

    public static function clear(): void
    {
        service('tenantContext')->clear();
    }
}