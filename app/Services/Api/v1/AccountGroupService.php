<?php

namespace App\Services\Api\V1;

use App\Exceptions\DBTransactionException;
use App\Models\AccountGroup;
use Exception;

class AccountGroupService
{
    public function getAccountGroupList()
    {
        return AccountGroup::all();
    }

    public function getAccountGroup($accountGroup)
    {
        return $accountGroup;
    }

    public function createAccountGroup(array $attributes)
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
