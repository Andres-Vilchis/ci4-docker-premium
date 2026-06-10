<?php

use App\Libraries\TenantContext;

if (!function_exists('tenant')) {
    function tenant(): ?int
    {
        return TenantContext::getOrganizationId();
    }
}

if (!function_exists('set_tenant')) {
    function set_tenant(int $id): void
    {
        TenantContext::setOrganizationId($id);
    }
}