<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    public function getUserById(string $email): User
    {
        return User::where('email', $email)->first();
    }
}
