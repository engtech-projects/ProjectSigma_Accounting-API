<?php

namespace App\Enums;

enum AccountCategory: string
{
    case ASSET = 'asset';
    case EQUITY = 'equity';
    case EXPENSES = 'expenses';
    case INCOME = 'income';
    case LIABILITIES = 'liabilities';
    case REVENUE = 'revenue';
    case CAPITAL = 'capital';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
