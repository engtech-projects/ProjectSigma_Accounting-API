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
        return JournalBook::find($journal)->first();
    }
    public static function createJournalBook(array $attribute)
    {
        return DB::transaction(function () use ($attribute) {
            $JournalBook = JournalBook::create($attribute);
            $JournalBook->book_has_accounts()->attach($attribute['account_id']);
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
