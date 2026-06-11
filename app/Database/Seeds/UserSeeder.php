<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = new UserModel();

        $existing = $users->where('username', 'admin')->first();

        if ($existing) {
            echo "User admin already exists\n";
            return;
        }

        $user = new User([
            'username' => 'admin',
            'active'   => 1,
        ]);

        $user->email = 'admin@test.com';
        $user->password = 'password123';

        $users->save($user);

        // opcional: asignar grupo
        $user = $users->findById($users->getInsertID());
        $user->addGroup('superadmin');

        echo "Admin user created\n";
    }
}