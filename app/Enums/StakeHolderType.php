<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum StakeHolderType: string
{
    use EnumHelper;

    case SUPPLIER = 'supplier';
    case EMPLOYEE = 'employee';
    case PROJECTS = 'project';
    case DEPARTMENT = 'department';
    case PAYEE = 'payee';
}
