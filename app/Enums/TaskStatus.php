<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';

    case COMPLETED = 'completed';

    case INCOMPLETE = 'incomplete';

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

