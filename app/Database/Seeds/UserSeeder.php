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

        // Buscar por username (más estable que credentials mix)
        $existing = $users->where('username', 'admin')->first();

        if ($existing) {
            echo "User admin already exists\n";
            return;
        }

        /**
         * IMPORTANTE:
         * Shield maneja email/password vía Entity hydration
         * NO se debe crear identity manual aquí.
         */
        $user = new User([
            'username' => 'admin',
            'active'   => 1,
        ]);

        // Password y email se asignan en entity (Shield intercepta y crea identity)
        $user->email    = 'admin@test.com';
        $user->password = 'password123';

        // Guardado correcto (genera users + auth_identities automáticamente)
        $users->save($user);

        echo "Admin user created\n";
    }
}