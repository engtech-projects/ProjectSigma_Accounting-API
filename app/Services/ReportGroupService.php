<?php

namespace App\Services;

use App\Http\Resources\ReportGroupCollection;
use App\Models\ReportGroup;

class ReportGroupService
{
    public static function getPaginated(array $paginatedData)
    {
        $query = ReportGroup::query();
        
        // Search by key (searches both name and description)
        if (!empty($paginatedData['key'])) {
            $query->where(function ($q) use ($paginatedData) {
                $q->where('name', 'like', '%' . $paginatedData['key'] . '%')
                  ->orWhere('description', 'like', '%' . $paginatedData['key'] . '%');
            });
        }
        
        // Specific name filter
        if (!empty($paginatedData['name'])) {
            $query->where('name', 'like', '%' . $paginatedData['name'] . '%');
        }
        
        // Specific description filter  
        if (!empty($paginatedData['description'])) {
            $query->where('description', 'like', '%' . $paginatedData['description'] . '%');
        }
        
        $paginatedResults = $query->paginate(config('services.pagination.limit', 15));

        return ReportGroupCollection::collection($paginatedResults)->response()->getData(true);
    }
}