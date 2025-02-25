<?php

namespace App\Services;

use App\Models\Term;

class TermsService
{
    public static function getPaginated(array $filters = [])
    {
        $query = Term::query();

        if (isset($filters['key'])) {
            $query->where('name', 'LIKE', "%{$filters['key']}%");
        }
        $query->with(['account']);

        return $query->paginate(config('services.pagination.limit'));
    }
}
