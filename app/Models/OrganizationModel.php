<?php

namespace App\Models;

use App\Models\BaseSaaSModel;

class OrganizationModel extends BaseSaaSModel
{
    protected $table = 'organizations';

    protected $allowedFields = [
        'name',
        'slug',
        'created_at',
        'updated_at'
    ];
}