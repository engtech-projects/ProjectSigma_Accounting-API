<?php

namespace App\Services;

use App\Enums\JournalStatus;
use App\Enums\VoucherType;
use App\Models\JournalEntry;
use Carbon\Carbon;

class JournalEntryService
{
    public static function getPaginated($validateData)
    {
        $query = JournalEntry::query();
        if (isset($validateData['status'])) {
            $query->status($validateData['status']);
        }

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function OpenEntries()
    {
        return JournalEntry::where('status', JournalStatus::OPEN->value)
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function voidEntries()
    {
        return JournalEntry::where('status', JournalStatus::VOID->value)
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function postedEntries()
    {
        return JournalEntry::where('status', JournalStatus::POSTED->value)
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function unpostedEntries()
    {
        return JournalEntry::where('status', JournalStatus::UNPOSTED->value)
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function draftedEntries()
    {
        return JournalEntry::where('status', JournalStatus::DRAFTED->value)
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function forVoucherEntriesListDisbursement()
    {
        return JournalEntry::where('status', JournalStatus::OPEN->value)
            ->whereDoesntHave('voucher')
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function forVoucherEntriesListCash()
    {
        return JournalEntry::where('status', JournalStatus::UNPOSTED->value)
            ->WhereHas('voucher')
            ->whereVoucherIsApproved()
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }
    public static function disbursementEntries()
    {
        return JournalEntry::whereHas('voucher', function ($query) {
                $query->where('type', VoucherType::DISBURSEMENT->value);
            })
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function forPaymentEnrtries()
    {
        return JournalEntry::where('status', JournalStatus::FOR_PAYMENT->value)
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }

    public static function CashEntries()
    {
        return JournalEntry::whereHas('voucher', function ($query) {
                $query->where('type', VoucherType::CASH->value);
            })
            ->withPaymentRequest()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));
    }
    public static function generateJournalDetails($details)
    {
        $journalData = collect($details)->map(function ($detail) {
            return [
                'stakeholder_id' => $detail['stakeholder_id'],
                'stakeholder' => $detail['stakeholder'],
                'journal_date' => $detail['journal_date'],
                'reference_no' => $detail['reference_no'],
                'payment_request_id' => $detail['payment_request_id'],
                'description' => $detail['description'],
                'remarks' => $detail['remarks'],
                'total' => $detail['total'],
            ];
        });
        return $journalData;
    }

    public static function generateJournalNumber(): string
    {
        $prefix = strtoupper('JE');
        $currentYearMonth = Carbon::now()->format('Y-m');
        // Find the highest series
        $lastJournal = JournalEntry::where('journal_no', 'like', "{$prefix}-{$currentYearMonth}-%")
            ->whereNull('deleted_at')
            ->orderBy('journal_no', 'desc')
            ->first();
        // Extract the last series number if a previous request exists
        if ($lastJournal) {
            $lastSeries = (int) substr($lastJournal->journal_no, -4); // Get last 4 digits
            $nextSeries = $lastSeries + 1;
        } else {
            $nextSeries = 1; // Start at 0001 if no previous voucher
        }
        // Format the series number to be 4 digits (e.g., 0001)
        $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
        // Construct the new reference number
        $journalNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";

        return $journalNo;
    }
}
