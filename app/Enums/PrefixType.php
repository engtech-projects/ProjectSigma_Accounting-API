<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum PrefixType: string
{
    use EnumHelper;
    case PRF_ACS = 'PRF-ACS';
    case PRF_PAYROLL = 'PRF-PAYROLL';
}
