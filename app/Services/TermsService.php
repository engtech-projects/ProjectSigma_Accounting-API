<?php

namespace App\Services;

use App\Models\Term;

class TermsService
{
    public static function getPaginated(array $filters = [])
    {
        $query = Term::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        $query->with(['account']);

        return $query->paginate(config('services.pagination.limit'));
    }
}
