<?php

namespace App\Enums;

enum VatType: string
{
    case VAT = 'VAT';
    case NON_VAT = 'NON-VAT';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
