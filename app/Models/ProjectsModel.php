<?php

namespace App\Models;

use App\Models\BaseTenantModel;

class ProjectsModel extends BaseTenantModel
{
    protected $table      = 'projects';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organization_id', // opcional (insert lo maneja igual)
        'name',
    ];

    protected $useTimestamps = true;
}