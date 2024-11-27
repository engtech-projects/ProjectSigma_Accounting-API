<?php

namespace App\Enums;

enum FormType: string
{
    case PAYMENT_REQUEST = 'PaymentRequest';
	case PAYROLL_REQUEST = 'PayrollRequest';
}
