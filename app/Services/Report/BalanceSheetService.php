<?php

namespace App\Services\Report;

use App\Enums\BalanceSheet;
use Carbon\Carbon;
use App\Enums\JournalStatus;
use App\Http\Resources\BalanceSheetResource;
use App\Models\Account;
use App\Models\JournalDetails;

class BalanceSheetService
{
    public static function balanceSheetReport($validate)
    {
        $dateFrom = Carbon::parse($validate["date_from"])->startOfDay();
        $dateTo = Carbon::parse($validate["date_to"])->endOfDay();

        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($dateFrom, $dateTo) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereBetween('created_at', [$dateFrom, $dateTo]);
        })
        ->with([
            'account.accountType',
            'account.reportGroup',
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
            return (object) [
                'account_id' => $firstTransaction->account_id,
                'account' => $firstTransaction->account,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'balance' => $totalDebit - $totalCredit,
            ];
        });
        $totalDebits = $journalDetails->sum(function ($detail) {
            return (float) ($detail->debit ?? 0);
        });
        $totalCredits = $journalDetails->sum(function ($detail) {
            return (float) ($detail->credit ?? 0);
        });
        $assetsTotal = Account::assets()->sum(function ($account) {
            return $account->balance;
        });
        $liabilitiesTotal = Account::liabilities()->sum(function ($account) {
            return -$account->balance;
        });
        $equityTotal = Account::equity()->sum(function ($account) {
            return -$account->balance;
        });
        $liabilitiesAndEquityTotal = $liabilitiesTotal + $equityTotal;
        $assetsForDisplay = Account::assets()->get()->map(function ($account) {
            return (object) [
                'account_id' => $account->account_id,
                'account' => $account->account,
                'debit' => $account->total_debit,
                'credit' => $account->total_credit,
                'balance' => $account->balance,
            ];
        });
        $liabilitiesForDisplay = Account::liabilities()->get()->map(function ($account) {
            return (object) [
                'account_id' => $account->account_id,
                'account' => $account->account,
                'debit' => $account->total_debit,
                'credit' => $account->total_credit,
                'balance' => $account->balance,
            ];
        });
        $equityForDisplay = Account::equity()->get()->map(function ($account) {
            return (object) [
                'account_id' => $account->account_id,
                'account' => $account->account,
                'debit' => $account->total_debit,
                'credit' => $account->total_credit,
                'balance' => $account->balance,
            ];
        });
        $liabilitiesAndEquityForDisplay = $liabilitiesForDisplay->merge($equityForDisplay);
        return [
            'success' => true,
            'message' => 'Balance Sheet Report Successfully Retrieved.',
            'data' => [
                'assets' => BalanceSheetResource::collection($assetsForDisplay)->resolve(),
                'liabilities_and_equity' => BalanceSheetResource::collection($liabilitiesAndEquityForDisplay)->resolve(),
                'totals' => [
                    'total_debits' => (float) number_format($totalDebits, 2, '.', ''),
                    'total_credits' => (float) number_format($totalCredits, 2, '.', ''),
                    'assets_total' => (float) number_format($assetsTotal, 2, '.', ''),
                    'liabilities_total' => (float) number_format($liabilitiesTotal, 2, '.', ''),
                    'equity_total' => (float) number_format($equityTotal, 2, '.', ''),
                    'liabilities_and_equity_total' => (float) number_format($liabilitiesAndEquityTotal, 2, '.', ''),
                    'balance' => (float) number_format($assetsTotal - $liabilitiesAndEquityTotal, 2, '.', ''),
                ],
            ],
            'date_range' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d'),
            ],
        ];
    }
}
