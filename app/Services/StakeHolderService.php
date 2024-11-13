<?php

namespace App\Services;

use App\Models\StakeHolder;

class StakeHolderService
{
    public static function searchStakeHolders(array $validatedData)
    {
        return StakeHolder::where('name', 'like', '%'. strtolower($validatedData['key']) .'%')
            ->where('stakeholdable_type', "App\Models\Stakeholders\\" . ucfirst($validatedData['type']))
            ->paginate(config('app.pagination_limit'));
    }
}
