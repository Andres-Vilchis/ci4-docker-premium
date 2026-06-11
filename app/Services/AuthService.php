<?php

namespace App\Services;

use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class AuthService
{
    public function __construct(
        protected UserModel $users = new UserModel()
    ) {}

    public function userExists(string $username): bool
    {
        return $this->users->where('username', $username)->first() !== null;
    }

    public function createUser(User $user, string $email, string $password): User
    {
        $user->email    = $email;
        $user->password = $password;

        $this->users->save($user);

        return $this->users->findById($this->users->getInsertID());
    }
}