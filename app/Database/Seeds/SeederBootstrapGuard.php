<?php

namespace App\Database\Seeds;

use Config\Database;

class SeederBootstrapGuard
{
    public static function assertReady(): void
    {
        $db = Database::connect();

        $requiredTables = [
            'users',
            'auth_identities',
            'organizations',
            'organization_users'
        ];

        foreach ($requiredTables as $table) {
            if (!$db->tableExists($table)) {
                throw new \RuntimeException(
                    "[SeederBootstrapGuard] Missing table: {$table}. Run migrations first."
                );
            }
        }
    }
}
