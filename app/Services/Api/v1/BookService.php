<?php

namespace App\Services\Api\v1;

use Exception;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DBTransactionException;





class BookService
{
    protected $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }
    public function getBookList()
    {
        return $this->book->all();
    }
    public function getBookById(Book $book)
    {
        return $book->with(['book_accounts'])->first();
    }
    public function createBook(array $attribute)
    {
        try {
            DB::transaction(function () use ($attribute) {
                return Book::create($attribute)->book_accounts()->attach($attribute['account_id']);
            });

        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }
    }
    public function updateBook($book, array $attributes)
    {
        try {
            DB::transaction(function () use ($attributes, $book) {
                $book->fill($attributes)->update();
                $book->book_accounts()->sync($attributes["account_id"]);
            });
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }


    }
    public function deleteBook(Book $book)
    {
        try {
            $book->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }
    }



}
