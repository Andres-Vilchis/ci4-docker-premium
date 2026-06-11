<?php

namespace App\Services;

use Config\Database;

class TenantMembershipService
{
    public static function getOrganizationsForUser(
        int $userId
    ): array {
        return Database::connect()
            ->table('organization_users ou')
            ->select(
                '
                o.id AS organization_id,
                o.name,
                o.slug,
                ou.role
                '
            )
            ->join(
                'organizations o',
                'o.id = ou.organization_id'
            )
            ->where(
                'ou.user_id',
                $userId
            )
            ->where(
                'ou.deleted_at',
                null
            )
            ->get()
            ->getResultArray();
    }

    public static function userBelongsToOrganization(
        int $userId,
        int $organizationId
    ): bool {
        return Database::connect()
            ->table('organization_users')
            ->where(
                'user_id',
                $userId
            )
            ->where(
                'organization_id',
                $organizationId
            )
            ->where(
                'deleted_at',
                null
            )
            ->countAllResults() > 0;
    }

    public static function getActiveOrganization(): ?array
    {
        $orgId = TenantSessionService::get();

        if (!$orgId) {
            return null;
        }

        return Database::connect()
            ->table('organizations')
            ->where('id', $orgId)
            ->get()
            ->getRowArray();
    }
}