<?php

namespace App\Enums\Reports;

enum BalanceSheet: string
{
    case ASSET = 'asset';
    case EQUITY = 'equity';
    case LIABILITIES = 'liabilities';
}
