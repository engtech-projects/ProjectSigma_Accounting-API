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
    public function getAll()
    {
        return $this->book->all();
    }
    public function getById(Book $book)
    {
        return $book->with(['book_accounts'])->first();
    }
    public function createBook(array $attribute)
    {

        DB::transaction(function () use ($attribute) {
            $book = Book::create($attribute);
            $book->book_accounts()->attach($attribute['account_id']);
            $book->account_group_books()->attach($attribute['account_group_id']);
        });

    }
    public function updateBook($book, array $attributes)
    {
        DB::transaction(function () use ($attributes, $book) {
            $book->fill($attributes);
            $book->update();
            $book->book_accounts()
                ->sync($attributes["account_id"]);
            $book->account_group_books()
                ->sync($attributes['account_group_id']);
        });

    }


}
