<?php

namespace App\Services\Reports;

use App\Models\JournalDetails;

class MonthlyUnliquidatedCashAdvanceService
{
    public static function unliquidatedCashAdvanceReport($dateFrom, $dateTo)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry.paymentRequest', function ($query) use ($dateFrom, $dateTo) {
            $query->unposted()
                ->whereBetween('created_at', [$dateFrom, $dateTo]);
        })
        ->with([
            'account.accountType',
            'account.reportGroup',
            'account.subGroup'
        ])
        ->get();
        $groupedData = $journalDetails;
        return [
            'success' => true,
            'message' => 'Income Statement Report Successfully Retrieved.',
            'data' => $groupedData,
            'raw_data' => $journalDetails,
            'date_range' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d'),
            ],
        ];
    }
}
