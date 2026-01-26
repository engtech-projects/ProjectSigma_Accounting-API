<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ParticularsType: string
{
    use EnumHelper;
    case CASH_IN_BANK = 'CASH IN BANK';
    case ACCOUNTS_PAYABLE = 'ACCOUNTS PAYABLE';
}
