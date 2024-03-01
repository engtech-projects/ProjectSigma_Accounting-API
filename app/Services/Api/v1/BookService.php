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
        /*  if ($relation) {
             $query->with($relation);
         } */
        $query->account_book_group;
        return $paginate ? $query->paginate(10) : $query->get();
    }


    public function getById(Book $book, ?array $relation = [])
    {

        $query = $book->query();
        if ($relation) {
            $query->with($relation);
        }
        return $query->find($book)->first();
    }


    public function createBook(array $attribute)
    {

        DB::transaction(function () use ($attribute) {
            $book = Book::create($attribute);
            $book->account()->attach($attribute['account_id']);
            $book->book_group()->attach([
                $attribute['account_group_id']
            ]);
        });

    }
    public function updateBook($book, array $attributes)
    {
        DB::transaction(function () use ($attributes, $book) {
            $book->fill($attributes);
            $book->update();
            $book->account()
                ->sync($attributes["account_id"]);
            $book->book_group()
                ->sync($attributes['account_group_id']);
        });

    }


}
