<?php

namespace App\Services\Reports;

use App\Models\ReportGroup;
use Illuminate\Support\Carbon;

class OfficeExpenseService
{
    public static function officeExpenseReport($dateFrom, $dateTo)
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();

        return ReportGroup::whereHas('accounts', function ($query) {
            $query->where('account_name', 'like', '%(OFFICE)%');
        })
            ->with([
                'accounts' => function ($query) use ($startDate, $endDate) {
                    $query->where('account_name', 'like', '%(OFFICE)%')
                        ->with([
                            'journalEntryDetails' => function ($detailQuery) use ($startDate, $endDate) {
                                $detailQuery->whereHas('journalEntryDetails', function ($entryQuery) use ($startDate, $endDate) {
                                    $entryQuery->whereBetween(
                                        'entry_date',
                                        [$startDate, $endDate]
                                    );
                                })
                                    ->with('journalEntryDetails');
                            }
                        ]);
                }
            ])
            ->get()
            ->map(function ($group) {
                return [
                    'report_group' => $group->name,
                    'accounts' => $group->accounts->map(function ($account) {
                        return [
                            'id' => $account->id,
                            'account_name' => $account->account_name,
                            'journal_entry_details' => $account->journalEntryDetails,
                        ];
                    })->values(),
                ];
            })
            ->values()
            ->toArray();
    }
}
