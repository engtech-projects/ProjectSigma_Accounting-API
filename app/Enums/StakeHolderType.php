<?php

namespace App\Enums;

enum StakeHolderType: string
{
    case SUPPLIER = 'supplier';
    case EMPLOYEE = 'employee';
    case PROJECTS = 'projects';
    case DEPARTMENT = 'department';
    case PAYEE = 'payee';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
