<?php

namespace App\Enums;

enum BalanceSheet: string
{
    case ASSET = 'asset';
    case EQUITY = 'equity';
    case LIABILITIES = 'liabilities';
}
