<?php

namespace App\Services;

use App\Http\Resources\AccountingCollections\StakeholderCollection;
use App\Models\StakeHolder;
use App\Models\Stakeholders\Payee;

class StakeHolderService
{
    public static function getPaginated(array $validateData)
    {
        $queryStakeholdersRequest = StakeHolder::when(isset($validateData['key']), function ($query, $key) use ($validateData) {
            $query->where('name', 'like', '%'.$validateData['key'].'%');
        })
            ->paginate(config('services.pagination.limit'));

        return StakeholderCollection::collection($queryStakeholdersRequest)->response()->getData(true);
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
