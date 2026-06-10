<?php

namespace App\Services;

use Config\Database;

class OrganizationResolverService
{
    public static function getUserOrganizations(int $userId): array
    {
        $db = Database::connect();

        return $db->table('organization_users ou')
            ->select('o.id, o.name, o.slug')
            ->join('organizations o', 'o.id = ou.organization_id')
            ->where('ou.user_id', $userId)
            ->get()
            ->getResultArray();
    }

    public static function getActiveOrganization(): ?array
    {
        $orgId = TenantSessionService::get();

        if (!$orgId) {
            return null;
        }

        $db = Database::connect();

        return $db->table('organizations')
            ->where('id', $orgId)
            ->get()
            ->getRowArray();
    }
}