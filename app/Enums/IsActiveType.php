<?php

namespace App\Enums;

enum IsActiveType: string
{
    case TRUE = '1';
    case FALSE = '0';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
