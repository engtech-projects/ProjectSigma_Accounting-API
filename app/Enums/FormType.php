<?php

namespace App\Enums;

enum FormType: string
{
    case PaymentRequest = 'PaymentRequest';
	case PayrollRequest = 'PayrollRequest';
}
