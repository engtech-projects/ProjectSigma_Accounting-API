<?php

namespace App\Enums;

enum PrefixType: string
{
    case RFA_ACS = 'RFA-ACS';
    case RFA_PAYROLL = 'RFA-PAYROLL';
    case PRF_ACS = 'PRF-ACS';
    case PRF_PAYROLL = 'PRF-PAYROLL';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
