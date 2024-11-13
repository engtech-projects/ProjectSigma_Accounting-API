<?php

namespace App\Services;

use App\Models\Account;
use App\Http\Resources\AccountingCollections\AccountCollection;

class AccountService
{
    public static function getPaginated(array $filters = [])
    {
        $query = Account::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['account_type_id'])) {
            $query->where('account_type_id', $filters['account_type_id']);
        }
        return $query->paginate(config('services.pagination.limit'));
    }
}
