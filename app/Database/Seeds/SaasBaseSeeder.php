<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Models\UserModel;

class SaasBaseSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        if (!$db->tableExists('users')) {
            throw new \RuntimeException(
                'Shield not installed correctly. Run: php spark shield:migrate'
            );
        }

        /*
        |---------------------------------------------
        | ORGANIZATION
        |---------------------------------------------
        */
        $org = $db->table('organizations')
            ->where('slug', 'default-org')
            ->get()
            ->getRowArray();

        if (!$org) {
            $db->table('organizations')->insert([
                'name'       => 'Default Organization',
                'slug'       => 'default-org',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $orgId = $db->insertID();
        } else {
            $orgId = $org['id'];
        }

        /*
        |---------------------------------------------
        | USER (ROBUST IDENTITY CHECK)
        |---------------------------------------------
        */
        $users = new UserModel();

        // FIX: usar email directo en identities table
        $existing = $users
            ->where('username', 'admin')
            ->first();

        if (!$existing) {

            $users->save([
                'username' => 'admin',
                'active'   => 1,
            ]);

            $userId = $users->getInsertID();

            $user = $users->findById($userId);
            $user->email = 'admin@local.test';
            $user->password = 'Password123!';
            $users->save($user);

        } else {
            $userId = $existing->id;
        }

        /*
        |---------------------------------------------
        | RELACIÓN ORG - USER
        |---------------------------------------------
        */
        $exists = $db->table('organization_users')
            ->where('organization_id', $orgId)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$exists) {
            $db->table('organization_users')->insert([
                'organization_id' => $orgId,
                'user_id'         => $userId,
                'role'            => 'owner',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }
    }
}