<?php

namespace App\Enums;

enum VoucherStatus: string
{
    case DRAFT = "disbursement";
    case CASH = "cash";
}
