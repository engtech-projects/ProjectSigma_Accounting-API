<?php

namespace App\Services\Api\V1;
use App\Models\JournalBook;
use Illuminate\Support\Facades\DB;

class JournalBookService
{
    public static function getJournalBookList()
    {
        return JournalBook::all();
    }
    public static function getJournalBook(JournalBook $journal)
    {
        return JournalBook::with('account')->find($journal)->first();
    }
    public static function createJournalBook(array $attribute)
    {
        return DB::transaction(function () use ($attribute) {
            JournalBook::create($attribute);
        });
    }
    public static function updateJournalBook(JournalBook $journalBook, array $attribute)
    {
        return DB::transaction(function () use ($journalBook, $attribute) {
            $journalBook->update($attribute);
        });

    }
    public static function deleteJournalBook(JournalBook $journalBook)
    {
        return DB::transaction(function () use ($journalBook) {
            $journalBook->delete();
        });
    }



}
