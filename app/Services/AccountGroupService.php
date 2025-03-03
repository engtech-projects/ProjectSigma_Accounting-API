<?php

namespace App\Services;

use App\Models\AccountGroup;
use App\Models\AccountGroupAccount;

class AccountGroupService
{
    public static function getPaginated(array $filters = [])
    {
        $query = AccountGroup::query();
        if (isset($filters['key'])) {
            $query->where('name', 'like', '%'.$filters['key'].'%');
        }

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function isExistAccountGroupAccount(int $accountGroupId)
    {
        return AccountGroupAccount::where('account_group_id', $accountGroupId)->exists();
    }
}
