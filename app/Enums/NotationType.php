<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum NotationType: string
{
    use EnumHelper;
    case POSITIVE = '+';
    case NEGATIVE = '-';
}
