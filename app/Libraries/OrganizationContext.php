<?php

namespace App\Libraries;

use App\Models\OrganizationUserModel;

class OrganizationContext
{
    /**
     * HARD SOURCE OF TRUTH: user memberships
     */
    public static function currentUserOrganizations(int $userId): array
    {
        return (new OrganizationUserModel())
            ->where('user_id', $userId)
            ->findAll();
    }

    /**
     * STRICT ACCESS CHECK (NO SOFT LOGIC)
     */
    public static function canAccess(int $userId, int $organizationId): bool
    {
        return (new OrganizationUserModel())
            ->where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->countAllResults() > 0;
    }

    /**
     * STRICT ROLE RESOLUTION
     */
    public static function role(int $userId, int $organizationId): ?string
    {
        $row = (new OrganizationUserModel())
            ->where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->first();

        return $row['role'] ?? null;
    }

    /**
     * HARD GUARD: throws if invalid access
     */
    public static function assertAccess(int $userId, int $organizationId): void
    {
        if (!self::canAccess($userId, $organizationId)) {
            throw new \RuntimeException(
                "ACCESS DENIED: user {$userId} not in organization {$organizationId}"
            );
        }
    }
}