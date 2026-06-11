<?php

namespace App\Services;

use CodeIgniter\Shield\Models\UserModel;

class RoleService
{
    public function assignAdmin(int $userId): void
    {
        $users = new UserModel();

        $user = $users->findById($userId);

        if ($user) {
            $user->addGroup('superadmin');
            $users->save($user);
        }
    }
}