<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

abstract class BaseTenantModel extends Model
{
    protected string $tenantField = 'organization_id';

    /**
     * Tenant modes:
     * - strict: bloquea acceso sin tenant
     * - soft: no filtra si no hay tenant
     * - disabled: ignora completamente tenant
     */
    protected string $tenantMode = 'strict';

    public function setTenantMode(string $mode): self
    {
        $this->tenantMode = $mode;
        return $this;
    }

    public function getTenantMode(): string
    {
        return $this->tenantMode;
    }

    /**
     * 🔥 CENTRAL QUERY OVERRIDE (CI4 compatible)
     */
    public function builder(?string $table = null, bool $forWrite = false): BaseBuilder
    {
        $builder = parent::builder($table, $forWrite);

        return $this->applyTenantScope($builder);
    }

    /**
     * CORE TENANT LOGIC
     */
    protected function applyTenantScope(BaseBuilder $builder): BaseBuilder
    {
        $ctx = service('tenantContext');
        $tenantId = $ctx->tenantId();

        // DISABLED: sin restricciones
        if ($this->tenantMode === 'disabled') {
            return $builder;
        }

        // STRICT: si no hay tenant, bloquear todo
        if ($this->tenantMode === 'strict' && !$tenantId) {
            return $builder->where('1 = 0');
        }

        // SOFT: si no hay tenant, no filtra
        if (!$tenantId) {
            return $builder;
        }

        if (!in_array($this->tenantField, $this->allowedFields, true)) {
            return $builder;
        }

        return $builder->where($this->tenantField, $tenantId);
    }

    /**
     * IMPORTANT: NO override findAll sin scope real
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        return parent::findAll($limit, $offset);
    }

    public function find($id = null)
    {
        return parent::find($id);
    }

    /**
     * AUTO-INJECT TENANT ON WRITE
     */
    public function insert($data = null, bool $returnID = true)
    {
        $ctx = service('tenantContext');
        $tenantId = $ctx->tenantId();

        if (is_array($data) && $tenantId) {
            if (in_array($this->tenantField, $this->allowedFields, true)) {
                $data[$this->tenantField] = $tenantId;
            }
        }

        return parent::insert($data, $returnID);
    }

    public function update($id = null, $data = null): bool
    {
        return parent::update($id, $data);
    }

    public function delete($id = null, bool $purge = false): bool
    {
        return parent::delete($id, $purge);
    }
}