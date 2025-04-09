<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';

    case COMPLETED = 'completed';

    case INCOMPLETE = 'incomplete';

    public static function values(): array
    {
        $statuses = [];

        foreach (self::cases() as $status) {
            $status[] = $status->value;
        }

        return $statuses;
    }
}

