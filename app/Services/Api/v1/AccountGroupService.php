<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\AccountGroup;
use Exception;

class AccountGroupService
{
    public function getAll(?bool $paginate = false, ?array $relation = [])
    {
        $query = AccountGroup::query();
        if ($relation) {
            $query->with($relation);
        }
        return $paginate ? $query->paginate(10) : $query->get();
    }

    public function getById(AccountGroup $accountGroup, ?array $relation = [])
    {
        if ($relation) {
            $accountGroup->load($relation);
        }
        return $accountGroup;
    }

    public static function create(array $attributes)
    {
        try {
            AccountGroup::create($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", $e);
        }

    }

    public function updateAccountGroup($accountGroup, array $attributes)
    {
        try {
            $accountGroup->fill($attributes)->update();
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", $e);
        }
    }

    public function deleteAccountGroup($accountGroup)
    {
        try {
            $accountGroup->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", $e);
        }
    }
}
