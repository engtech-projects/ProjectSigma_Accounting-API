<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum FormType: string
{
    use EnumHelper;

    case PAYMENT_REQUEST = 'PaymentRequest';
    case PAYROLL_REQUEST = 'PayrollRequest';
}
