<?php

namespace App\Enums;

enum NotationType: string
{
    case POSITIVE = '+';
    case NEGATIVE = '-';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
