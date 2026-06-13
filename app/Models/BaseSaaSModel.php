<?php

namespace App\Models;

use App\Services\TenantContextService;
use CodeIgniter\Model;

/**
 * @deprecated Use BaseTenantModel instead.
 * Kept only for backward compatibility during refactor.
 */
class BaseSaaSModel extends Model
{
    protected bool $skipOrgScope = false;

    public function builder($table = null)
    {
        $builder = parent::builder($table);

        if ($this->skipOrgScope) {
            return $builder;
        }

        if (TenantContextService::hasTenant()) {
            $builder->where(
                'organization_id',
                TenantContextService::organizationId()
            );
        }

        return $builder;
    }

    public function withoutTenant(): static
    {
        $this->skipOrgScope = true;
        return $this;
    }
}