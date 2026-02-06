<?php

namespace App\Services\Reports;

use App\Models\ReportGroup;
use Illuminate\Support\Carbon;

class OfficeCodeService
{
    public static function officeCodeReport($dateFrom, $dateTo)
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
                                    $entryQuery->whereBetween(
                                        'entry_date',
                                        [$startDate, $endDate]
                                    );
                                })
                                    ->with('journalEntry');
                            }
                        ]);
                }
            ])
            ->get()
            ->map(function ($group) use (&$grandTotal) {
                $accounts = $group->accounts->map(function ($account) use (&$grandTotal) {
                    $hasDebit = $account->journalEntryDetails->where('debit', '>', 0)->count() > 0;
                    $totalAmount = $hasDebit
                        ? $account->journalEntryDetails->sum('debit')
                        : $account->journalEntryDetails->sum('credit');

                    $grandTotal += $totalAmount;

                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'journal_entry_details' => $account->journalEntryDetails,
                        'total_amount' => number_format($totalAmount, 2, '.', ''),
                    ];
                })->values();
                return [
                    'report_group' => $group->name,
                    'accounts' => $accounts,
                ];
            })
            ->values()
            ->toArray();
        $reportData[] = [
            'grand_total' => number_format($grandTotal, 2, '.', ''),
        ];
        return $reportData;
    }
}
