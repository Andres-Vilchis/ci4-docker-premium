<?php

namespace App\Database\Query;

use App\Services\TenantContextService;
use RuntimeException;

class TenantGuard
{
    public static function enforce(array $data): array
    {
        $tenantId = TenantContextService::require();

        if (!$tenantId) {
            throw new RuntimeException(
                'Tenant missing in query context'
            );
        }

        $data['organization_id'] = $tenantId;

        return $data;
    }
}
