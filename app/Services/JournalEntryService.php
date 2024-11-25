<?php

namespace App\Services;

use App\Enums\JournalStatus;
use App\Models\JournalEntry;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

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
    public static function unpostedEntries()
    {
        return JournalEntry::where('status', JournalStatus::UNPOSTED->value)
            ->paginate(config('services.pagination.limit'));
    }

    public static function postedEntries()
    {
        return JournalEntry::where('status', JournalStatus::POSTED->value)
            ->paginate(config('services.pagination.limit'));
    }

    public static function draftedEntries()
    {
        return JournalEntry::where('status', JournalStatus::DRAFTED->value)
            ->paginate(config('services.pagination.limit'));
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
            $lastSeries = (int) substr($lastJournal->journal_number, -4); // Get last 4 digits
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
