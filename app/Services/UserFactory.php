<?php

namespace App\Services;

use CodeIgniter\Shield\Entities\User;

class UserFactory
{
    public function create(string $username): User
    {
        return new User([
            'username' => $username,
            'active'   => 1,
        ]);
    }
}