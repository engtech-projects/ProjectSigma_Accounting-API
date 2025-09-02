<?php

namespace App\Services;

use App\Models\PostingPeriod;

class PeriodService
{
    public static function getPaginated(array $filters = [])
    {
        $query = (new PostingPeriod)->newQuery();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        return $query->withDetails()->orderByDesc('created_at')->paginate(config('services.pagination.limit'));
    }
}
