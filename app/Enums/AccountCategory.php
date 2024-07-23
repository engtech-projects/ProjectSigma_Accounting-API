<?php

namespace App\Enums;

enum AccountCategory: string
{
    case ASSET = "asset";
    case EQUITY = "equity";
    case EXPENSE = "expenses";
    case INCOME = "income";
    case REVENUE = "revenue";
    case LIABILITY = "liabilities";
    case CAPITAL = "capital";

}