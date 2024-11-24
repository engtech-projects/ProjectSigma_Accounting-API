<?php

namespace App\Services;

use App\Enums\JournalStatus;
use App\Models\JournalEntry;
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
        $latestJournal = JournalEntry::withTrashed(false)->latest()->first();
        $nextNumber = $latestJournal ? intval(substr($latestJournal->journal_number, 2)) + 1 : 1;
        return 'JE-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
    }
}
