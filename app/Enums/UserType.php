<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum UserType: string
{
    use EnumHelper;

    case EMPLOYEE = 'Employee';
    case ADMINISTRATOR = 'Administrator';
}
