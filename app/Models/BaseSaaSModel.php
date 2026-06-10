<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Traits\HasOrganizationScope;

class BaseSaaSModel extends Model
{
    use HasOrganizationScope;

    protected bool $skipOrgScope = false;

    protected function initialize()
    {
        parent::initialize();
        $this->initializeHasOrganizationScope();
    }

    /**
     * Admin override (optional)
     */
    public function withoutTenant(): self
    {
        $this->applyOrgScope = false;
        return $this;
    }
}