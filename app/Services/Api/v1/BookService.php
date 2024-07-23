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
    public function getAll(?bool $paginate = false, ?array $relation = [])
    {
        $query = $this->book->query();
        if ($relation) {
            $query->with($relation);
        }
        return $paginate ? $query->paginate(10) : $query->get();
    }


    public function getById(Book $book, ?array $relation = [])
    {

        if ($relation) {
            $book->load($relation);
        }
        return $book;
    }


    public function createBook(array $attribute)
    {

        DB::transaction(function () use ($attribute) {
            $book = Book::create($attribute);
            $book->accounts()->attach($attribute['account_ids']);
            $book->account_group_books()->attach([
                $attribute['account_group_id']
            ]);
        });
    }
    public function updateBook($book, array $attributes)
    {
        DB::transaction(function () use ($attributes, $book) {
            $book->fill($attributes);
            $book->update();
            $book->accounts()
                ->sync($attributes["account_ids"]);
            $book->account_group_books()
                ->sync($attributes['account_group_id']);
        });
    }
}
