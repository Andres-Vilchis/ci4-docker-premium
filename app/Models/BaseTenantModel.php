<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Services\TenantContextService;

abstract class BaseTenantModel extends Model
{
    protected string $tenantField = 'organization_id';

    protected function builder($table = null)
    {
        $builder = parent::builder($table);

        if (TenantContextService::hasTenant()) {
            $builder->where($this->tenantField, TenantContextService::organizationId());
        }

        return $builder;
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (TenantContextService::hasTenant()) {
            $data[$this->tenantField] = TenantContextService::organizationId();
        }

        return parent::insert($data, $returnID);
    }
}