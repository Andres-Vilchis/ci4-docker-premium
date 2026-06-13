<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        $exists = $db->table('users')
            ->where('username', 'admin')
            ->get()
            ->getRowArray();

        if ($exists) {
            echo "User admin already exists\n";
            return;
        }

        $db->table('users')->insert([
            'username'   => 'admin',
            'active'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $userId = $db->insertID();

        $db->table('auth_groups_users')->insert([
            'user_id' => $userId,
            'group'   => 'superadmin',
        ]);

        echo "Admin user created\n";
    }
}