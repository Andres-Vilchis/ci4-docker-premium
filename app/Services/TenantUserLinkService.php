<?php

namespace App\Services;

use App\Models\OrganizationUserModel;

class TenantUserLinkService
{
    public function __construct(
        protected OrganizationUserModel $model = new OrganizationUserModel()
    ) {}

    public function link(int $userId, int $organizationId, string $role = 'admin'): void
    {
        $exists = $this->model
            ->where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->first();

        if ($exists) {
            return;
        }

        $this->model->insert([
            'user_id'         => $userId,
            'organization_id' => $organizationId,
            'role'            => $role,
        ]);
    }
}