<?php

namespace App\Services;

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
}
