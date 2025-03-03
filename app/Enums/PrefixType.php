<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum PrefixType: string
{
    use EnumHelper;

    case RFA_ACS = 'RFA-ACS';
    case RFA_PAYROLL = 'RFA-PAYROLL';
    case PRF_ACS = 'PRF-ACS';
    case PRF_PAYROLL = 'PRF-PAYROLL';

}
