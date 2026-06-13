<?php

namespace App\Services;

use App\Services\TenantSessionService;

/**
 * Unified bootstrap for HTTP + CLI
 */
class TenantKernel
{
    public static function boot(): void
    {
        $tenantId = null;

        // HTTP session source
        if (function_exists('session')) {
            $tenantId = TenantSessionService::get();
        }

        // CLI fallback (env override)
        if (!$tenantId && getenv('TENANT_ID')) {
            $tenantId = (int) getenv('TENANT_ID');
        }

        if (!$tenantId) {
            return;
        }

        TenantContextService::set((int) $tenantId);
    }

    public static function requireTenant(): int
    {
        return TenantContextService::require();
    }
}