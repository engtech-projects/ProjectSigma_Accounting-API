<?php

namespace App\Enums;

enum MainModuleType: string
{
    case ACCOUNTING = 'accounting';
    case INVENTORY = 'inventory';
    case HRMS = 'hrms';
    case PROJECT = 'project';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
