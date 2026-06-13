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

        $user->email = 'andresvilchis@gmail.com';
        $user->password = 'admin.P4ss';

        $users->save($user);

        $userId = $users->where('username', 'admin')->first()->id;

        $userEntity = $users->findById($userId);
        $userEntity->addGroup('superadmin');

        echo "Admin user created\n";
    }
}