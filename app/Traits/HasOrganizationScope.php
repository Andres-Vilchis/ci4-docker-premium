<?php

namespace App\Traits;

use App\Services\TenantContextService;

trait HasOrganizationScope
{

    protected function initializeHasOrganizationScope()
    {
        $this->beforeInsert(function (array $data) {

            $tenantId = TenantContextService::get();

            if (!$tenantId) {
                return $data;
            }

            // hard guard: only if column exists in schema mapping
            if (!in_array('organization_id', $this->allowedFields ?? [])) {
                return $data;
            }

            $data['data']['organization_id'] = $tenantId;

            return $data;
        });
    }

    protected function applyTenantWhere($builder)
    {
        $tenantId = TenantContextService::get();

        if (!$tenantId) {
            return $builder;
        }

        if (!in_array('organization_id', $this->allowedFields ?? [])) {
            return $builder;
        }

        return $builder->where('organization_id', $tenantId);
    }
}