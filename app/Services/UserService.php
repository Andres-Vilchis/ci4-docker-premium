<?php

namespace App\Services;

use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UserService
{
    public static function createAdmin(
        string $email,
        string $password,
        string $username
    ): User {
        $users = model(UserModel::class);

        $user = new User([
            'email'    => $email,
            'username' => $username,
            'active'   => 1,
        ]);

        $users->save($user);

        $user->password = $password;
        $users->save($user);

        return $user;
    }
}