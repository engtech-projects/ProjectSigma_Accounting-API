<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public static function getPaginated(array $filters = [])
    {
        $query = Book::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate(config('app.pagination_limit'));
    }
}
