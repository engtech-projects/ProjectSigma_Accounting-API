<?php

namespace App\Enums\Reports;

enum BalanceSheet: string
{
    case ASSET = 'asset';
    case EQUITY = 'equity';
    case LIABILITIES = 'liabilities';
    case CURRENT_ASSET = 'current_asset';
    case NON_CURRENT_ASSET = 'non_current_asset';
    case CURRENT_LIABILITIES = 'current_liabilities';
}
