<?php

namespace App\Models;

class OrganizationUserModel extends TenantModel
{
    protected $table = 'organization_users';

    protected $allowedFields = [
        'organization_id',
        'user_id',
        'role',
        'created_at'
    ];
}