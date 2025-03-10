<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum LocationType: string
{
    use EnumHelper;

    case DEPARTMENT = 'OFFICE';
    case PROJECT = 'PROJECT';
}
