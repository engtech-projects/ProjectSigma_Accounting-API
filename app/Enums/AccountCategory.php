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
}
