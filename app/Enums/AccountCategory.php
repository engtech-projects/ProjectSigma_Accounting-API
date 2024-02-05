<?php

namespace App\Enums;

enum AccountCategory: string
{
    case ASSET = "asset";
    case EQUITY = "equity";
    case EXPENSE = "expense";
    case INCOME = "income";
    case LIABILITY = "liability";

}
