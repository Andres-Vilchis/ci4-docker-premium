<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Database\Seeds\SeederBootstrapGuard;

class SaasBaseSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        SeederBootstrapGuard::assertReady();

        /*
        | ORGANIZATION
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
        | USER (RAW SAFE SHIELD INSERT)
        */
        $identity = $db->table('auth_identities')
            ->where('secret', 'admin@local.test')
            ->get()
            ->getRowArray();

        if (!$identity) {

            $db->table('users')->insert([
                'username' => 'admin',
                'active'   => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $userId = $db->insertID();

            $db->table('auth_identities')->insert([
                'user_id' => $userId,
                'type'    => 'email_password',
                'secret'  => 'admin@local.test',
                'secret2' => password_hash('Password123!', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        } else {
            $userId = $identity['user_id'];
        }

        /*
        | RELATION
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