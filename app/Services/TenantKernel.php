<?php

namespace App\Services;

class TenantKernel
{
    public static function boot(): void
    {
        $tenantId = session()->get('active_organization_id');
        $user = auth()->user();

        if (!$tenantId || !$user) {
            return;
        }

        service('tenantContext')
            ->setTenantId((int) $tenantId)
            ->setUser($user);
    }

    public static function requireTenant(): int
    {
        return service('tenantContext')->requireTenantId();
    }
}