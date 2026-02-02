<?php

namespace App\Services\Reports;

use App\Enums\JournalStatus;
use App\Models\JournalDetails;

class IncomeStatementService
{
    public static function incomeStatementReport($dateFrom, $dateTo)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($dateFrom, $dateTo) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereBetween('created_at', [$dateFrom, $dateTo]);
        })
        ->with([
            'account.accountType',
            'account.reportGroup',
            'account.subGroup'
        ])
        ->get();
        $groupedData = self::groupByCategory($journalDetails);
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
    private static function groupByCategory($journalDetails)
    {
        $revenues = [];
        $expenses = [];
        foreach ($journalDetails as $detail) {
            if (!$detail->account || !$detail->account->accountType || !$detail->account->reportGroup) {
                continue;
            }
            $accountType = $detail->account->accountType;
            $reportGroup = $detail->account->reportGroup;
            $amount = self::calculateAmount($detail, $accountType->account_category);
            if ($amount == 0) {
                continue;
            }
            if (in_array($accountType->account_category, ['revenue', 'income'])) {
                if (!isset($revenues[$reportGroup->name])) {
                    $revenues[$reportGroup->name] = [
                        'report_group' => $reportGroup->name,
                        'total' => 0,
                        'accounts' => []
                    ];
                }
                $accountKey = $detail->account->account_name;
                if (!isset($revenues[$reportGroup->name]['accounts'][$accountKey])) {
                    $revenues[$reportGroup->name]['accounts'][$accountKey] = [
                        'account_number' => $detail->account->account_number,
                        'account_name' => $detail->account->account_name,
                        'total' => 0,
                        'entries' => []
                    ];
                }
                $revenues[$reportGroup->name]['accounts'][$accountKey]['total'] += $amount;
                $revenues[$reportGroup->name]['accounts'][$accountKey]['entries'][] = [
                    'description' => $detail->description,
                    'debit' => $detail->debit ?? 0,
                    'credit' => $detail->credit ?? 0,
                    'amount' => $amount,
                    'date' => $detail->created_at
                ];
                $revenues[$reportGroup->name]['total'] += $amount;
            } elseif ($accountType->account_category === 'expenses') {
                if (!isset($expenses[$reportGroup->name])) {
                    $expenses[$reportGroup->name] = [
                        'report_group' => $reportGroup->name,
                        'total' => 0,
                        'accounts' => []
                    ];
                }
                $accountKey = $detail->account->account_name;
                if (!isset($expenses[$reportGroup->name]['accounts'][$accountKey])) {
                    $expenses[$reportGroup->name]['accounts'][$accountKey] = [
                        'account_number' => $detail->account->account_number,
                        'account_name' => $detail->account->account_name,
                        'total' => 0,
                        'entries' => []
                    ];
                }
                $expenses[$reportGroup->name]['accounts'][$accountKey]['total'] += $amount;
                $expenses[$reportGroup->name]['accounts'][$accountKey]['entries'][] = [
                    'description' => $detail->description,
                    'debit' => $detail->debit ?? 0,
                    'credit' => $detail->credit ?? 0,
                    'amount' => $amount,
                    'date' => $detail->created_at
                ];
                $expenses[$reportGroup->name]['total'] += $amount;
            }
        }
        foreach ($revenues as &$revenueGroup) {
            $revenueGroup['accounts'] = array_values($revenueGroup['accounts']);
        }
        foreach ($expenses as &$expenseGroup) {
            $expenseGroup['accounts'] = array_values($expenseGroup['accounts']);
        }
        $totalRevenue = array_sum(array_column($revenues, 'total'));
        $totalExpenses = array_sum(array_column($expenses, 'total'));
        $incomeBeforeTax = $totalRevenue - $totalExpenses;
        return [
            'revenues' => array_values($revenues),
            'expenses' => array_values($expenses),
            'summary' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_expenses' => round($totalExpenses, 2),
                'income_before_tax' => round($incomeBeforeTax, 2),
                'income_tax_expense' => 0,
                'net_income' => round($incomeBeforeTax, 2),
            ]
        ];
    }

    private static function calculateAmount($detail, $accountCategory)
    {
        $debit = $detail->debit ?? 0;
        $credit = $detail->credit ?? 0;
        if (in_array($accountCategory, ['revenue', 'income'])) {
            return $credit - $debit;
        }
        if ($accountCategory === 'expenses') {
            return $debit - $credit;
        }
        return 0;
    }
}
