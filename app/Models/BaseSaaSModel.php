<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Services\TenantContextService;

class BaseSaaSModel extends Model
{

    protected bool $skipOrgScope = false;

    public function builder($table = null)
    {
        $builder = parent::builder($table);

        return $this->applyTenantScope($builder);
    }

    protected function applyTenantScope($builder)
    {
        if ($this->skipOrgScope) {
            return $builder;
        }

        $tenantId = TenantContextService::get();

        if (!$tenantId) {
            return $builder;
        }

        return $builder->where($this->getTenantField(), $tenantId);
    }

    public function withoutTenant(): static
    {
        $this->skipOrgScope = true;
        return $this;
    }

    protected function getTenantField(): string
    {
        return 'organization_id';
    }
}
