<?php

namespace App\Services\Reports;

use App\Enums\JournalStatus;
use App\Enums\Reports\BalanceSheet;
use App\Models\JournalDetails;

class OfficeExpenseService
{
    public function officeExpenseReport($startDate, $endDate)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($startDate, $endDate){
            $query->where('status', JournalStatus::POSTED->value)
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->with([
            'account.accountType',
            'account.reportGroup',
            'account.subGroup',
        ])
        ->get();
        $officeExpenses = $journalDetails->groupBy('account_id')->map(function ($transactions){
            $firstTransaction = $transactions->first();

            $totalDebit = $transactions->sum(function ($t) {
                return (float) ($t->debit ?? 0);
            });
            $totalCredit = $transactions->sum(function ($t) {
                return (float) ($t->credit ?? 0);
            });

            $accountCategory = $firstTransaction->account->accounType->account_category ?? null;
        });
    }
}

