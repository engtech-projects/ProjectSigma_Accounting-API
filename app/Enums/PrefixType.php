<?php

namespace App\Enums;

enum PrefixType: string
{
    case RFA_ACCTG = 'RFA-ACCTG';
    case RFA_PAYROLL = 'RFA-PAYROLL';
    case PRF_ACCTG = 'PRF-ACCTG';
    case PRF_PAYROLL = 'PRF-PAYROLL';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
