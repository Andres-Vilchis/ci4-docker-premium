<?php

namespace App\Models;

use App\Services\TenantContextService;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

abstract class BaseTenantModel extends Model
{
    protected string $tenantField = 'organization_id';

    protected function applyTenant(BaseBuilder $builder): BaseBuilder
    {
        if (!TenantContextService::hasTenant()) {
            return $builder;
        }

        return $builder->where(
            $this->tenantField,
            TenantContextService::organizationId()
        );
    }

    public function builder(?string $table = null): BaseBuilder
    {
        return $this->applyTenant(parent::builder($table));
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && TenantContextService::hasTenant()) {
            $data[$this->tenantField] = TenantContextService::organizationId();
        }

        return parent::insert($data, $returnID);
    }

    // FIX CRÍTICO: update isolation
    public function update($id = null, $data = null): bool
    {
        $this->applyTenant($this->builder());

        return parent::update($id, $data);
    }

    // FIX CRÍTICO: delete isolation
    public function delete($id = null, bool $purge = false): bool
    {
        $this->applyTenant($this->builder());

        return parent::delete($id, $purge);
    }

    // SAFE FIND override
    public function find($id = null)
    {
        $builder = $this->applyTenant($this->builder());

        if ($id !== null) {
            return $builder->where($this->primaryKey, $id)->get()->getRow();
        }

        return parent::find($id);
    }
}