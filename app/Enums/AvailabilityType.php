<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum AvailabilityType: string
{
    use EnumHelper;

    case PICK_UP = 'PICK_UP';
    case DELIVER_ON_SITE = 'DELIVER_ON_SITE';
    case FOR_SHIPMENT = 'FOR_SHIPMENT';
}
