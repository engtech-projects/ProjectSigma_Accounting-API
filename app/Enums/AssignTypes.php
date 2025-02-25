<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum AssignTypes: string
{
    use EnumHelper;
    case DEPARTMENT = 'Department';
    case PROJECT = 'Project';
}
