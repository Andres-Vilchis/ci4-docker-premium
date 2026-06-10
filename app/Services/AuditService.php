<?php

namespace App\Services;

use Config\Database;

class AuditService
{
    public static function log(string $action, string $entity, ?int $entityId = null, array $metadata = []): void
    {
        $db = Database::connect();

        $db->table('audit_logs')->insert([
            'organization_id' => TenantContextService::get(),
            'user_id'        => auth()->id(),
            'action'         => $action,
            'entity'         => $entity,
            'entity_id'      => $entityId,
            'metadata'       => json_encode($metadata),
            'ip_address'     => service('request')->getIPAddress(),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }
}