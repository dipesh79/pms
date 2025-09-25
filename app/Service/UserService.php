<?php

namespace App\Service;

use App\Models\User;

class UserService
{
    public function createUser(array $data): User
    {
        return User::query()->create($data);
    }
}
