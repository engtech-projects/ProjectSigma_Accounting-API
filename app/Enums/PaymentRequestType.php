<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum PaymentRequestType: string
{
    use EnumHelper;

    case PRF = 'prf';
    case PAYROLL = 'payroll';
    case PO = 'po';
}
