<?php

use App\Services\TenantContextService;

if (!function_exists('tenant')) {
    function tenant(): ?int
    {
        return TenantContextService::get();
    }
}

if (!function_exists('set_tenant')) {
    function set_tenant(int $id): void
    {
        TenantContextService::set($id);
    }
}