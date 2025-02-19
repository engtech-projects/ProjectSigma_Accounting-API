<?php

namespace App\Enums;

enum WtaxType: string
{
    case GOODS = 'GOODS';
    case SERVICES = 'SERVICES';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
