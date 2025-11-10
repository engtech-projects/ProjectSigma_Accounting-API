<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum AvailabilityType: string
{
    use EnumHelper;
    case AVAILABLE = 'AVAILABLE';
    case UNAVAILABLE = 'UNAVAILABLE';
    case ORDER_BASIS_7_DAYS = 'ORDER_BASIS_7_DAYS';
    case ORDER_BASIS_15_DAYS = 'ORDER_BASIS_15_DAYS';
    case ORDER_BASIS_30_DAYS = 'ORDER_BASIS_30_DAYS';
}
