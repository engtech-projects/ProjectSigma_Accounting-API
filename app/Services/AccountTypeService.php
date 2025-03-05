<?php

namespace App\Services;

use App\Http\Resources\AccountTypeCollection;
use App\Models\AccountType;

class AccountTypeService
{
    public static function getPaginated(array $validatedData)
    {
        $queryAccountTypeRequestFilter = AccountType::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            $query->where('account_type', 'like', '%'.$validatedData['key'].'%');
        })
            ->paginate(config('services.pagination.limit'));

        return AccountTypeCollection::collection($queryAccountTypeRequestFilter)->response()->getData(true);
    }
}
