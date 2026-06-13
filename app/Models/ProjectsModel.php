<?php

namespace App\Models;

class ProjectsModel extends BaseTenantModel
{
    protected $table      = 'projects';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'organization_id',
        'name',
    ];

    protected $useTimestamps = true;
}