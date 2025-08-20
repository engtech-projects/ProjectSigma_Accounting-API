<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum VoucherType: string
{
    use EnumHelper;

    case CASH = 'Cash';
    case DISBURSEMENT = 'Disbursement';
    case CASH_CODE = 'CV';
    case DISBURSEMENT_CODE = 'DV';
}
