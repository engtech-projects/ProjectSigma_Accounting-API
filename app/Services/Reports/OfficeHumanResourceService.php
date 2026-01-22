<?php

namespace App\Services\Reports;

use App\Models\ReportGroup;
use Illuminate\Support\Carbon;

class OfficeHumanResourceService
{
    public static function officeHumanResource($dateFrom, $dateTo)
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();
        $grandTotal = 0;
        $reportData = ReportGroup::whereHas('accounts', function ($query) {
            $query->where('account_name', 'like', '%(OFFICE)%');
        })
            ->with([
                'accounts' => function ($query) use ($startDate, $endDate) {
                    $query->where('account_name', 'like', '%(OFFICE)%')
                        ->with([
                            'subGroup',
                            'journalEntryDetails' => function ($detailQuery) use ($startDate, $endDate) {
                                $detailQuery->whereHas('journalEntry', function ($entryQuery) use ($startDate, $endDate) {
                                    $entryQuery->whereBetween('entry_date', [$startDate, $endDate]);
                                })->with('journalEntry');
                            }
                        ]);
                }
            ])
            ->get()
            ->map(function ($group) use (&$grandTotal) {
                $budgetTotal = 0;
                $actualTotal = 0;
                $accounts = $group->accounts->map(function ($account) use (&$budgetTotal, &$actualTotal) {
                    $totalDebit = $account->journalEntryDetails->sum('debit');
                    $totalCredit = $account->journalEntryDetails->sum('credit');
                    $hasDebit = $totalDebit > 0;
                    $budget = $hasDebit ? $totalDebit : $totalCredit;
                    $actual = $hasDebit ? $totalCredit : $totalDebit;
                    $balance = $budget - $actual;
                    $budgetTotal += $budget;
                    $actualTotal += $actual;
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'journal_entry_details' => $account->journalEntryDetails,
                        'budget' => number_format($budget, 2, '.', ''),
                        'actual' => number_format($actual, 2, '.', ''),
                        'balance' => number_format($balance, 2, '.', ''),
                    ];
                })->values();
                $groupBalance = $budgetTotal - $actualTotal;
                $grandTotal += $budgetTotal;
                return [
                    'report_group' => $group->name,
                    'accounts' => $accounts,
                    'budget_total' => number_format($budgetTotal, 2, '.', ''),
                    'actual_total' => number_format($actualTotal, 2, '.', ''),
                    'balance_total' => number_format($groupBalance, 2, '.', ''),
                ];
            })
            ->values()
            ->toArray();
        return $reportData;
    }
}
