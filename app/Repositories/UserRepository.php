<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    public function getUserByEmail($email): mixed
    {
        return User::where('email', $email)->first();
    }
}
