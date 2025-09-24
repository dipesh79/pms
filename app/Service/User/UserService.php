<?php

namespace App\Service\User;

use App\Models\User;

class UserService
{
    public function createUser(array $data): User
    {
        return User::query()->create($data);
    }
}
