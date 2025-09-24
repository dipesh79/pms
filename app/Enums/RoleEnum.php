<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';

    public static function toArray(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
