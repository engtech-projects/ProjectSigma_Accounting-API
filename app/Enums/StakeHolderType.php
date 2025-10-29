<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Payee;
use App\Models\Stakeholders\Project;
use App\Models\Stakeholders\Supplier;

enum StakeHolderType: string
{
    use EnumHelper;

    case SUPPLIER = 'supplier';
    case EMPLOYEE = 'employee';
    case PROJECTS = 'project';
    case DEPARTMENT = 'department';
    case PAYEE = 'payee';

    public function getModelClass(): string
    {
        return match($this) {
            self::EMPLOYEE => Employee::class,
            self::PAYEE => Payee::class,
            self::SUPPLIER => Supplier::class,
            self::DEPARTMENT => Department::class,
            self::PROJECTS => Project::class,
        };
    }
}
