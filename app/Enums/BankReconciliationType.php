<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum BankReconciliationType: string
{
    use EnumHelper;

    case YES = 'yes';
    case NO = 'no';
}
