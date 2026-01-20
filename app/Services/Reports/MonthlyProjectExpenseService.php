<?php

namespace App\Services\Reports;

use App\Models\JournalDetails;
use App\Enums\JournalStatus;

class MonthlyProjectExpensesService
{
    public static function monthlyProjectExpenseReport($year)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($year) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereYear('created_at', $year);
        })
        ->with([
            'stakeholder',
            'account.accountType',
            'account.reportGroup',
            'account.subGroup'
        ])
        ->get();
        return [
            'success' => true,
            'message' => 'Monthly Project Expense Report Successfully Retrieved.',
            'data' => $journalDetails,
            'date_range' => [
                'year' => $year,
            ],
        ];
    }
}
