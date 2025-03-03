<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum AccountVatType: string
{
    use EnumHelper;

    case ACCOUNT_INPUT_VAT = 'INPUT VAT';
    case OUTPUT_VAT_PAYABLE = 'OUTPUT VAT PAYABLE';
    case DEFERRED_VAT_PAYABLE = 'DEFERRED VAT PAYABLE';
    case CONSTRUCTION_REVENUE_PRIVATE_SALES = 'CONSTRUCTION REVENUE- PRIVATE SALES';
}
