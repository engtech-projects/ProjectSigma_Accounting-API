<?php

namespace App\Enums;

enum AccountCategory: string
{
    case ASSET = "asset";
    case EQUITY = "equity";
    case EXPENSE = "expenses";
    case INCOME = "income";
    case LIABILITY = "liabilities";
    case CAPITAL = "capital";

}
'asset','equity','expenses','income','liabilities','revenue','capital'