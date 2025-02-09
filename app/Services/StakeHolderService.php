<?php

namespace App\Services;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Payee;

class StakeHolderService
{
    public static function searchStakeHolders(array $validatedData)
    {
        return StakeHolder::where('name', 'like', '%'.strtolower($validatedData['key']).'%')
            ->where('stakeholdable_type', "App\Models\Stakeholders\\".ucfirst($validatedData['type']))
            ->paginate(config('app.pagination_limit'));
    }

    public static function getPaginated(array $filters = [])
    {
        $query = StakeHolder::query();
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (isset($filters['type'])) {
            $query->where('stakeholdable_type', "App\Models\Stakeholders\\".ucfirst($filters['type']));
        }

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function createPayee($data)
    {
        $lastPayee = Payee::orderBy('id', 'desc')->first();
        $id = $lastPayee ? $lastPayee->id + 1 : 1;
        Payee::create([
            'id' => $id,
            'name' => $data['name'],
            'source_id' => $id,
        ]);

        return $id;
    }
    public static function findIdByNameOrNull($name)
    {
        $stakeholderName = StakeHolder::where('name', $name)->first();
        return $stakeholderName ? $stakeholderName->id : null;
    }
}
