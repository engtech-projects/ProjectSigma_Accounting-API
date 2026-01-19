<?php

namespace App\Services\Reports;

use App\Models\JournalDetails;
use App\Enums\JournalStatus;

class MonthlyProjectExpensesService
{
    public static function monthlyProjectExpenseReport($dateFrom, $dateTo)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($dateFrom, $dateTo) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereBetween('created_at', [$dateFrom, $dateTo]);
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
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d'),
            ],
        ];
    }
}
