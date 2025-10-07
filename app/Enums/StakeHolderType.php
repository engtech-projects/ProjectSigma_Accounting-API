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


    public function getModelClass(): string
    {
        return match($this) {
            self::EMPLOYEE => \App\Models\Stakeholders\Employee::class,
            self::PAYEE => \App\Models\Stakeholders\Payee::class,
            self::SUPPLIER => \App\Models\Stakeholders\Supplier::class,
            self::DEPARTMENT => \App\Models\Stakeholders\Department::class,
            self::PROJECTS => \App\Models\Stakeholders\Project::class,
        };
    }
}
