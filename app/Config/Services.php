<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Libraries\TenantContext;

class Services extends BaseService
{
    public static function tenantContext(bool $getShared = true): TenantContext
    {
        if ($getShared) {
            return static::getSharedInstance('tenantContext');
        }

        return new TenantContext();
    }
}