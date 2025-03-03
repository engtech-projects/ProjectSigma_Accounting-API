<?php

namespace App\Services;

use App\Http\Resources\ParticularGroupCollection;
use App\Models\ParticularGroup;

class ParticularGroupService
{
    public static function getWithPagination(array $validatedData)
    {
        $particularGroupRequest = ParticularGroup::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('name', 'LIKE', "%{$validatedData['key']}%");
        })
            ->paginate(config('services.pagination.limit'));

        return ParticularGroupCollection::collection($particularGroupRequest)->response()->getData(true);
    }
}
