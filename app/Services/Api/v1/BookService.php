<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\Book;
use Exception;





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
            $book = Book::create($attribute);
            $book->book_accounts()->attach($attribute['account_id']);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }
    }
    public function updateBook(Book $book, array $attribute)
    {
        try {
            $book->update($attribute);
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
