<?php

namespace App\Libraries;

use App\Models\OrganizationUserModel;

class OrganizationContext
{
    public static function currentUserOrganizations(int $userId): array
    {
        $model = new OrganizationUserModel();

        return $model
            ->where('user_id', $userId)
            ->findAll();
    }

    public static function canAccess(int $userId, int $organizationId): bool
    {
        $model = new OrganizationUserModel();

        return $model
            ->where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->countAllResults() > 0;
    }

    public static function role(int $userId, int $organizationId): ?string
    {
        $model = new OrganizationUserModel();

        $row = $model
            ->where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->first();

        return $row['role'] ?? null;
    }
}