<?php

namespace App\Database\Query;

use App\Services\TenantContextService;

class TenantGuard
{
    public static function enforce(array $data): array
    {
        $tenantId = TenantContextService::get();

        if (!$tenantId) {
            throw new \RuntimeException('Tenant missing in query context');
        }

        $data['organization_id'] = $tenantId;

        return $data;
    }
}