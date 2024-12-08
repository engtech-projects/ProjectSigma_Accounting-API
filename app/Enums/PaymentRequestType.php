<?php

namespace App\Enums;

enum PaymentRequestType: string
{
    case PRF = 'prf';
    case PAYROLL = 'payroll';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
