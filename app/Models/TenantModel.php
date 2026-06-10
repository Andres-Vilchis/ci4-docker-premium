<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Services\TenantContextService;

abstract class TenantModel extends Model
{
    protected string $tenantField = 'organization_id';

    protected bool $useTenantScope = true;

    protected function initialize()
    {
        parent::initialize();

        if ($this->useTenantScope) {
            $this->builder()->where($this->tenantField, TenantContextService::require());
        }
    }

    protected function insertData(array $data): bool
    {
        if ($this->useTenantScope) {
            $data[$this->tenantField] = TenantContextService::require();
        }

        return parent::insertData($data);
    }

    protected function updateData(array $data, $where): bool
    {
        if ($this->useTenantScope) {
            $this->builder()->where($this->tenantField, TenantContextService::require());
        }

        return parent::updateData($data, $where);
    }
}