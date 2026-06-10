<?php

namespace App\Traits;

use App\Libraries\TenantContext;

trait HasOrganizationScope
{
    protected bool $applyOrgScope = true;

    protected function initializeHasOrganizationScope()
    {
        $this->beforeInsert(function ($data) {

            if (!isset($data['data']['organization_id'])) {
                $data['data']['organization_id'] = TenantContext::get();
            }

            return $data;
        });

        $this->beforeFind(function ($builder) {

            if ($this->applyOrgScope && TenantContext::get()) {
                $builder->where('organization_id', TenantContext::get());
            }

            return $builder;
        });
    }
}