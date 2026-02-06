<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum PaymentTermTypes: string
{
    use EnumHelper;

    case PRE_PAYMENT_IN_FULL = 'PRE_PAYMENT_IN_FULL';
    case CREDIT_7_DAYS = 'CREDIT_7_DAYS';
    case CREDIT_15_DAYS = 'CREDIT_15_DAYS';
    case CREDIT_30_DAYS = 'CREDIT_30_DAYS';
    case PROGRESS_BILLING = 'PROGRESS_BILLING';
}
