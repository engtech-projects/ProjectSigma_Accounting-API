<?php

namespace App\Services;

use App\Models\AccountGroup;
use App\Http\Resources\AccountingCollections\AccountGroupCollection;

class AccountGroupService
{
    public static function getPaginated(array $filters = [])
    {
        $query = AccountGroup::query();
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        return $query->paginate(config('services.pagination.limit'));
    }
}
