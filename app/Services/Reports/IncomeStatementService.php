<?php

namespace App\Services\Reports;

use App\Enums\JournalStatus;
use App\Enums\Reports\IncomeStatement;
use App\Models\JournalDetails;

class IncomeStatementService
{
    public static function incomeStatementReport($startDate, $endDate)
    {
        $incomeStatementReportData = [
            IncomeStatement::CONSTRUCTION_COST_DEPRECIATION_AMORTIZATION,
            IncomeStatement::CONSTRUCTION_COST_DEPRECIATION_AMORTIZATION,
            IncomeStatement::CONSTRUCTION_COST_OVERHEAD,
            IncomeStatement::CONSTRUCTION_COST_MATERIALS,
            IncomeStatement::CONSTRUCTION_COST_EQUIPMENT_RENTAL,
        ];

        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->with([
            'account.accountType',
            'account.reportGroup',
            'account.subGroup' => function ($query) use ($incomeStatementReportData) {
                $query->whereIn('name', $incomeStatementReportData)->get();
            },
        ])
        ->get();

        return [
            'success' => true,
            'message' => 'Balance Sheet Report Successfully Retrieved.',
            'data' => $journalDetails,
            'date_range' => [
                'from' => $startDate->format('Y-m-d'),
                'to' => $endDate->format('Y-m-d'),
            ],
        ];
    }
}
