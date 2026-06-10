<?php

namespace App\Services;

use App\Services\TenantContextService;

class TenantKernel
{
    public static function boot(): void
    {
        $tenantId = session()->get('active_organization_id');

        if (!$tenantId) {
            return;
        }

        TenantContextService::set($tenantId);
    }

    public static function requireTenant(): int
    {
        $id = TenantContextService::get();

        if (!$id) {
            throw new \RuntimeException('Tenant not set (access denied)');
        }

        return $id;
    }
}