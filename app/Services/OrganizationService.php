<?php

namespace App\Services;

use App\Models\OrganizationModel;

class OrganizationService
{
    public function __construct(
        protected OrganizationModel $model = new OrganizationModel()
    ) {}

    public function createDefault(string $name = 'Default Org'): int
    {
        $exists = $this->model->where('slug', 'default-org')->first();

        if ($exists) {
            return (int) $exists['id'];
        }

        return $this->model->insert([
            'name' => $name,
            'slug' => 'default-org',
        ], true);
    }
}