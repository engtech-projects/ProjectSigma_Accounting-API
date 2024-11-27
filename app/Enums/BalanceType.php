<?php

namespace App\Enums;

enum BalanceType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
