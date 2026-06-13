<?php

namespace App\Models;

use App\Services\TenantContextService;
use CodeIgniter\Model;

/**
 * Tenant-aware model (OPT-IN)
 *
 * This model does NOT modify global CI4 behavior.
 * It applies tenant scope only when explicitly enabled.
 */
abstract class TenantAwareModel extends Model
{
    protected string $tenantField = 'organization_id';

    protected bool $applyTenantScope = true;

    protected function applyTenant(array $data): array
    {
        if (!$this->applyTenantScope) {
            return $data;
        }

        if (!TenantContextService::hasTenant()) {
            return $data;
        }

        $data[$this->tenantField] = TenantContextService::require();

        return $data;
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            $data = $this->applyTenant($data);
        }

        return parent::insert($data, $returnID);
    }

    /**
     * Disable tenant scope for admin/system queries
     */
    public function withoutTenant(): static
    {
        $this->applyTenantScope = false;
        return $this;
    }
}