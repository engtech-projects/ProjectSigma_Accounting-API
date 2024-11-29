<?php

namespace App\Services;

use App\Models\AccountType;

class AccountTypeService
{
    public static function getPaginated(array $filters = [])
    {
        $query = AccountType::query();
        if (isset($filters['account_type'])) {
            $query->where('account_type', 'like', '%'.$filters['account_type'].'%');
        }

        return $query->paginate(config('services.pagination.limit'));
    }
}
