<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum IsActiveType: string
{
    use EnumHelper;

    case TRUE = '1';
    case FALSE = '0';
}
