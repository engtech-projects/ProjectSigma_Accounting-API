<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum MainModuleType: string
{
    use EnumHelper;

    case ACCOUNTING = 'accounting';
    case INVENTORY = 'inventory';
    case HRMS = 'hrms';
    case PROJECT = 'project';
}
