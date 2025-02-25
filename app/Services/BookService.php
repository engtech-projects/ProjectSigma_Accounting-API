<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public static function getPaginated(array $filters = [])
    {
        $query = Book::query();

        if (isset($filters['key'])) {
            $query->where('name', 'like', "%{$filters['key']}%");
        }

        return $query->paginate(config('app.pagination_limit'));
    }
}
