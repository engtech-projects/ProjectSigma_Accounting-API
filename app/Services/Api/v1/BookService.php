<?php

namespace App\Services\Api\V1;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookService
{
    public static function getBookList()
    {
        return Book::all();
    }
    public static function getBookById(Book $book)
    {
        return Book::find($book)->first();
    }
    public static function createBook(array $attribute)
    {
        return DB::transaction(function () use ($attribute) {
            $book = Book::create($attribute);
            $book->book_has_accounts()->attach($attribute['account_id']);
        });
    }
    public static function updateBook(Book $book, array $attribute)
    {
        return DB::transaction(function () use ($book, $attribute) {
            $book->update($attribute);
        });

    }
    public static function deleteBook(Book $book)
    {
        return DB::transaction(function () use ($book) {
            $book->delete();
        });
    }



}
