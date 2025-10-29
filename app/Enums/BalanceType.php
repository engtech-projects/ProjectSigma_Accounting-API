<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum BalanceType: string
{
    use EnumHelper;

    case DEBIT = 'debit';
    case CREDIT = 'credit';
}
