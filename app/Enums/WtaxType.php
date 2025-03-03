<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum WtaxType: string
{
    use EnumHelper;

    case GOODS = 'GOODS';
    case SERVICES = 'SERVICES';
}
