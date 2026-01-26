<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ReportType: string
{
    use EnumHelper;

    case BALANCE_SHEET = 'balance-sheet';
    case BOOK_BALANCE = 'book-balance';
    case INCOME_STATEMENT = 'income-statement';
    case STATEMENT_CASH_FLOW = 'statement-cash-flow';
    case OFFICE_CODE = 'office-code';
    case OFFICE_HUMAN_RESOURCE = 'office-human-resource';
    case MONTHLY_PROJECT_EXPENSES = 'monthly-project-expenses';
    case MONTHLY_UNLIQUIDATED_CASH_ADVANCE = 'monthly-unliquidated-cash-advance';
    case EXPENSES_FOR_THE_MONTH = 'expenses-for-the-month';
    case LIQUIDATION_FORM = 'liquidation-form';
    case REPLENISHMENT_SUMMARY = 'replenishment-summary';
    case CASH_ADVANCE_SUMMARY = 'cash-advance-summary';
    case MEMORANDUM_OF_DEPOSIT = 'memorandum-of-deposit';
    case PROVISIONAL_RECEIPT = 'provisional-receipt';
    case CASH_RETURN_SLIP = 'cash-return-slip';
    case PAYROLL_LIQUIDATIONS = 'payroll-liquidations';
    case BUDGET_REPORT = 'budget-report';
    case TRANSACTION_TALLY = 'transaction-tally';
}
