<?php

namespace App\Enums;

enum VoucherStatus: string
{
    case DISBURSEMENT = "disbursement";
    case CASH = "cash";
}
