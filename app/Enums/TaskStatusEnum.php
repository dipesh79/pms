<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';

    case DONE = 'done';

    public static function toArray(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
