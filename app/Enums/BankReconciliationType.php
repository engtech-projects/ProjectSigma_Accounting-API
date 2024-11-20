<?php

namespace App\Enums;

enum BankReconciliationType: string
{
    case YES = 'yes';
    case NO = 'no';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
