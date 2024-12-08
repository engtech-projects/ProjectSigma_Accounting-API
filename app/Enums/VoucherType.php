<?php

namespace App\Enums;

enum VoucherType: string
{
    case CASH = 'Cash';
    case DISBURSEMENT = 'Disbursement';
    case CASH_CODE = 'CV';
    case DISBURSEMENT_CODE = 'DV';
}
