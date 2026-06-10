<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Models\UserModel;

class SaasBaseSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        /*
        |--------------------------------------------------------------------------
        | 1. ORGANIZATION (IDEMPOTENT)
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | 2. USER (SHIELD SAFE CREATION)
        |--------------------------------------------------------------------------
        */
        $users = model(UserModel::class);

        $existingUser = $users->findByCredentials([
            'email' => 'admin@local.test',
        ]);

        if (!$existingUser) {

            $user = $users->save([
                'username' => 'admin',
                'active'   => 1,
            ]);

            $userId = $users->getInsertID();

            $userEntity = $users->findById($userId);
            $userEntity->email = 'admin@local.test';
            $userEntity->password = 'Password123!';

            $users->save($userEntity);

        } else {
            $userId = $existingUser->id;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. RELACIÓN ORGANIZATION - USER (IDEMPOTENT)
        |--------------------------------------------------------------------------
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