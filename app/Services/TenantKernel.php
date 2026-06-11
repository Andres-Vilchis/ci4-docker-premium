<?php

namespace App\Services;

class TenantKernel
{
    public static function boot(): void
    {
        $tenantId = session()->get('active_organization_id');

        if (!$tenantId) {
            return;
        }

        TenantContextService::set(
            (int) $tenantId
        );
    }

    public static function requireTenant(): int
    {
        return TenantContextService::require();
    }
}