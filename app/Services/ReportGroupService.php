<?php

namespace App\Services;

use App\Models\ReportGroup;

class ReportGroupService
{
    public static function getPaginated(array $filters = [])
    {
        $query = ReportGroup::query();

        if (!empty($filters['key'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['key'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['key'] . '%');
            });
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }

        return $query->orderByDesc('created_at')->paginate(config('services.pagination.limit'));
    }
}
