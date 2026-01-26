<?php

namespace App\Services\Reports;

use App\Models\JournalDetails;
use App\Enums\JournalStatus;
use Illuminate\Support\Facades\DB;

class MonthlyProjectExpensesService
{
    public static function monthlyProjectExpenseReport($year)
    {
        $journalDetails = JournalDetails::whereHas('journalEntry', function ($query) use ($year) {
            $query->where('status', JournalStatus::POSTED->value)
                ->whereYear('created_at', $year);
        })
        ->with([
            'stakeholder',
            'account',
            'journalEntry'
        ])
        ->get();

        $stakeholderData = [];
        $accountsData = [];

        foreach ($journalDetails as $detail) {
            $accountCode = $detail->account->code ?? 'Unknown';
            $stakeholderId = $detail->stakeholder_id ?? 'no_stakeholder';
            $stakeholderName = $detail->stakeholder->name ?? 'No Stakeholder';
            $month = date('F', strtotime($detail->journalEntry->created_at));
            if (!isset($stakeholderData[$stakeholderId])) {
                $stakeholderData[$stakeholderId] = [
                    'stakeholder_id' => $stakeholderId,
                    'stakeholder_name' => $stakeholderName,
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                    'total' => 0,
                ];
            }
            $stakeholderData[$stakeholderId][$month] += $detail->debit ?? 0;
            $stakeholderData[$stakeholderId]['total'] += $detail->debit ?? 0;
            if (!isset($accountsData[$accountCode])) {
                $accountsData[$accountCode] = [
                    'account_code' => $accountCode,
                    'account_name' => $detail->account->name ?? '',
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];
            }
            $accountsData[$accountCode][$month] += $detail->debit ?? 0;
        }
        $monthlyTotals = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];
        foreach ($stakeholderData as $stakeholder) {
            foreach ($monthlyTotals as $month => $total) {
                $monthlyTotals[$month] += $stakeholder[$month];
            }
        }
        $directCosts = [];
        $overhead = [];
        foreach ($accountsData as $code => $data) {
            if (strpos($code, 'DIRECT') !== false) {
                $directCosts[$code] = $data;
            } else {
                $overhead[$code] = $data;
            }
        }
        return [
            'success' => true,
            'message' => 'Monthly Project Expense Report Successfully Retrieved.',
            'data' => [
                'stakeholder_totals' => array_values($stakeholderData),
                'direct_costs' => array_values($directCosts),
                'overhead' => array_values($overhead),
                'all_accounts' => array_values($accountsData),
                'monthly_totals' => $monthlyTotals,
                'grand_total' => array_sum($monthlyTotals),
            ],
            'date_range' => [
                'year' => $year,
            ],
        ];
    }
    public static function monthlyProjectExpenseReportOptimized($year)
    {
        $stakeholderResults = JournalDetails::select(
            'stakeholder_id',
            DB::raw('MONTH(journal_entries.created_at) as month'),
            DB::raw('SUM(journal_details.debit) as total_debit')
        )
        ->join('journal_entries', 'journal_details.journal_entry_id', '=', 'journal_entries.id')
        ->where('journal_entries.status', JournalStatus::POSTED->value)
        ->whereYear('journal_entries.created_at', $year)
        ->groupBy('stakeholder_id', DB::raw('MONTH(journal_entries.created_at)'))
        ->with('stakeholder')
        ->get();
        $accountResults = JournalDetails::select(
            'account_id',
            DB::raw('MONTH(journal_entries.created_at) as month'),
            DB::raw('SUM(journal_details.debit) as total_debit')
        )
        ->join('journal_entries', 'journal_details.journal_entry_id', '=', 'journal_entries.id')
        ->where('journal_entries.status', JournalStatus::POSTED->value)
        ->whereYear('journal_entries.created_at', $year)
        ->groupBy('account_id', DB::raw('MONTH(journal_entries.created_at)'))
        ->with('account')
        ->get();
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $stakeholderData = [];
        foreach ($stakeholderResults as $result) {
            $stakeholderId = $result->stakeholder_id ?? 'no_stakeholder';
            $stakeholderName = $result->stakeholder->name ?? 'No Stakeholder';
            if (!isset($stakeholderData[$stakeholderId])) {
                $stakeholderData[$stakeholderId] = [
                    'stakeholder_id' => $stakeholderId,
                    'stakeholder_name' => $stakeholderName,
                    'total' => 0,
                ];
                foreach ($months as $month) {
                    $stakeholderData[$stakeholderId][$month] = 0;
                }
            }
            $monthName = $months[$result->month];
            $stakeholderData[$stakeholderId][$monthName] = $result->total_debit;
            $stakeholderData[$stakeholderId]['total'] += $result->total_debit;
        }
        $accountsData = [];
        foreach ($accountResults as $result) {
            $accountCode = $result->account->code ?? 'Unknown';

            if (!isset($accountsData[$accountCode])) {
                $accountsData[$accountCode] = [
                    'account_code' => $accountCode,
                    'account_name' => $result->account->name ?? '',
                ];
                foreach ($months as $month) {
                    $accountsData[$accountCode][$month] = 0;
                }
            }
            $monthName = $months[$result->month];
            $accountsData[$accountCode][$monthName] = $result->total_debit;
        }
        $monthlyTotals = array_fill_keys($months, 0);
        foreach ($stakeholderData as $stakeholder) {
            foreach ($months as $month) {
                $monthlyTotals[$month] += $stakeholder[$month];
            }
        }
        return [
            'success' => true,
            'message' => 'Monthly Project Expense Report Successfully Retrieved.',
            'data' => [
                'stakeholder_totals' => array_values($stakeholderData),
                'accounts' => array_values($accountsData),
                'monthly_totals' => $monthlyTotals,
                'grand_total' => array_sum($monthlyTotals),
            ],
            'date_range' => [
                'year' => $year,
            ],
        ];
    }
}
