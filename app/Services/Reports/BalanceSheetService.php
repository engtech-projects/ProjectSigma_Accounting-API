<?php

namespace App\Services\Reports;

use App\Enums\JournalStatus;
use App\Http\Resources\BalanceSheetCollection;
use App\Models\JournalDetails;
use App\Enums\Reports\BalanceSheet;

class BalanceSheetService
{
    public static function balanceSheetReport($startDate, $endDate)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->with([
            'account.accountType',
            'account.reportGroup',
            'account.subGroup',
        ])
        ->get();

        $accountBalances = $journalDetails->groupBy('account_id')->map(function ($transactions) {
            $firstTransaction = $transactions->first();

            $totalDebit = $transactions->sum(function ($t) {
                return (float) ($t->debit ?? 0);
            });
            $totalCredit = $transactions->sum(function ($t) {
                return (float) ($t->credit ?? 0);
            });

            $accountCategory = $firstTransaction->account->accountType->account_category ?? null;
            $balance = in_array($accountCategory, ['Asset', BalanceSheet::ASSET->value])
                ? $totalDebit - $totalCredit
                : $totalCredit - $totalDebit;

            return (object) [
                'account_id' => $firstTransaction->account_id,
                'account_name' => $firstTransaction->account->account_name ?? null,
                'account_type_id' => $firstTransaction->account->account_type_id ?? null,
                'report_group_id' => $firstTransaction->account->report_group_id ?? null,
                'report_group' => $firstTransaction->account->reportGroup ?? null,
                'sub_group_id' => $firstTransaction->account->sub_group_id ?? null,
                'sub_group' => $firstTransaction->account->subGroup ?? null,
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'balance' => $balance,
            ];
        })->values();

        $currentAssets = self::filterBySubGroup($accountBalances, BalanceSheet::CURRENT_ASSET->value);
        $nonCurrentAssets = self::filterBySubGroup($accountBalances, BalanceSheet::NON_CURRENT_ASSET->value);
        $liabilities = self::filterBySubGroup($accountBalances, BalanceSheet::CURRENT_LIABILITIES->value);
        $equity = self::filterBySubGroup($accountBalances, BalanceSheet::EQUITY->value);

        $currentAssetsTotal = $currentAssets->sum('balance');
        $nonCurrentAssetsTotal = $nonCurrentAssets->sum('balance');
        $totalAssets = $currentAssetsTotal + $nonCurrentAssetsTotal;
        $liabilitiesTotal = $liabilities->sum('balance');
        $equityTotal = $equity->sum('balance');
        $liabilitiesAndEquityTotal = $liabilitiesTotal + $equityTotal;

        $currentAssetsForDisplay = $currentAssets->values();
        $nonCurrentAssetsForDisplay = $nonCurrentAssets->values();
        $liabilitiesForDisplay = $liabilities->values();
        $equityForDisplay = $equity->values();
        $liabilitiesAndEquityForDisplay = $liabilitiesForDisplay->merge($equityForDisplay);

        return [
            'success' => true,
            'message' => 'Balance Sheet Report Successfully Retrieved.',
            'data' => [
                'current_assets' => BalanceSheetCollection::collection($currentAssetsForDisplay)->resolve(),
                'non_current_assets' => BalanceSheetCollection::collection($nonCurrentAssetsForDisplay)->resolve(),
                'liabilities' => BalanceSheetCollection::collection($liabilitiesForDisplay)->resolve(),
                'equity' => BalanceSheetCollection::collection($equityForDisplay)->resolve(),
                'liabilities_and_equity' => BalanceSheetCollection::collection($liabilitiesAndEquityForDisplay)->resolve(),
                'totals' => [
                    'current_assets_total' => (float) number_format($currentAssetsTotal, 2, '.', ''),
                    'non_current_assets_total' => (float) number_format($nonCurrentAssetsTotal, 2, '.', ''),
                    'total_assets' => (float) number_format($totalAssets, 2, '.', ''),
                    'liabilities_total' => (float) number_format($liabilitiesTotal, 2, '.', ''),
                    'equity_total' => (float) number_format($equityTotal, 2, '.', ''),
                    'liabilities_and_equity_total' => (float) number_format($liabilitiesAndEquityTotal, 2, '.', ''),
                    'balance' => (float) number_format($totalAssets - $liabilitiesAndEquityTotal, 2, '.', ''),
                ],
            ],
            'date_range' => [
                'from' => $startDate->format('Y-m-d'),
                'to' => $endDate->format('Y-m-d'),
            ],
        ];
    }
    private static function filterBySubGroup($accountBalances, string $subGroupName)
    {
        return $accountBalances->filter(function ($account) use ($subGroupName) {
            return $account->sub_group && $account->sub_group->name === $subGroupName;
        });
    }
}
