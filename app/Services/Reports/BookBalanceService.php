<?php

namespace App\Services\Reports;

use App\Models\Account;
use App\Models\JournalDetails;
use App\Models\User;

class BookBalanceService
{
    public static function bookBalanceReport($startDate, $endDate)
    {
        $cashInBankAccounts = Account::cashInBank()->with('accountType')->get();
        $journalDetails = JournalDetails::getJournalDetailsByDate($startDate, $endDate);
        $accountTransactions = $journalDetails->groupBy('account_id');
        $cashInBankBalances = $cashInBankAccounts->map(function ($account) use ($accountTransactions, $startDate) {
            $transactions = $accountTransactions->get($account->id, collect());
            $totalDebit = $transactions->sum(function ($t) {
                return (float) ($t->debit ?? 0);
            });
            $totalCredit = $transactions->sum(function ($t) {
                return (float) ($t->credit ?? 0);
            });
            $periodMovement = $totalDebit - $totalCredit;
            $openingBalance = self::getOpeningBalance($account->id, $startDate);
            $openingBalance = $openingBalance ?? 0;
            $closingBalance = $openingBalance + $periodMovement;
            $userId = auth()->user()->id;
            $user = User::find($userId);

            return [
                'account_id' => $account->id,
                'account_name' => $account->account_name ?? 'Unknown',
                'account_type_id' => $account->account_type_id,
                'account_type' => $account->accountType?->account_type ?? 'Unknown',
                'opening_balance' => round($openingBalance, 2),
                'debit' => round($totalDebit, 2),
                'credit' => round($totalCredit, 2),
                'closing_balance' => round($closingBalance, 2),
                'created_by' => $userId,
                'name' => $user ? $user->name : 'Unknown',
            ];
        });
        $cashInBankTotal = $cashInBankBalances->sum('closing_balance');
        return [
            'success' => true,
            'message' => 'Book Balance Report Successfully Retrieved.',
            'data' => [
                'date_from' => $startDate,
                'date_to' => $endDate,
                'cash_in_bank' => $cashInBankBalances->values(),
                'cash_in_bank_total' => round($cashInBankTotal, 2),
            ],
        ];
    }

    private static function getOpeningBalance($accountId, $startDate)
    {
        $openingBalances = JournalDetails::whereIn('account_id', [$accountId])
            ->whereHas('journalEntry', function ($query) use ($startDate) {
                $query->where('created_at', '<', $startDate);
            })
            ->get()
            ->groupBy('account_id')
            ->map(function ($transactions) {
                $totalDebit = $transactions->sum(fn ($t) => (float) ($t->debit ?? 0));
                $totalCredit = $transactions->sum(fn ($t) => (float) ($t->credit ?? 0));
                return $totalDebit - $totalCredit;
            });
    }
}
