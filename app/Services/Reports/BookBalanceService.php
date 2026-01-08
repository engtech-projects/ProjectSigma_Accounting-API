<?php

namespace App\Services\Reports;

use App\Enums\ParticularsType;
use App\Models\Account;
use App\Models\JournalDetails;

class BookBalanceService
{
    public static function bookBalanceReport($startDate, $endDate)
    {
        $cashInBankAccounts = Account::whereHas('accountType', function ($query) {
            $query->where('account_type', ParticularsType::CASH_IN_BANK->value);
        })->with('accountType')->get();
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
            $openingBalance = self::getOpeningBalance($account->id, $startDate);
            $periodMovement = $totalDebit - $totalCredit;
            $closingBalance = $openingBalance + $periodMovement;
            return [
                'account_id' => $account->id,
                'account_name' => $account->account_name ?? 'Unknown',
                'account_code' => $account->account_code ?? null,
                'account_type_id' => $account->account_type_id,
                'account_type' => $account->accountType->account_type,
                'opening_balance' => (float) number_format($openingBalance, 2, '.', ''),
                'debit' => (float) number_format($totalDebit, 2, '.', ''),
                'credit' => (float) number_format($totalCredit, 2, '.', ''),
                'closing_balance' => (float) number_format($closingBalance, 2, '.', ''),
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
                'cash_in_bank_total' => (float) number_format($cashInBankTotal, 2, '.', ''),
            ],
        ];
    }

    private static function getOpeningBalance($accountId, $startDate)
    {
        $transactions = JournalDetails::where('account_id', $accountId)
            ->whereHas('journalEntry', function ($query) use ($startDate) {
                $query->where('created_at', '<', $startDate);
            })
            ->get();
        $totalDebit = $transactions->sum(function ($t) {
            return (float) ($t->debit ?? 0);
        });
        $totalCredit = $transactions->sum(function ($t) {
            return (float) ($t->credit ?? 0);
        });
        return $totalDebit - $totalCredit;
    }
}
