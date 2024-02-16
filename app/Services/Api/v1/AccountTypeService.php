<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\AccountType;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountTypeService
{
    protected $accountType;
    public function __construct(AccountType $accountType)
    {
        $this->accountType = $accountType;
    }
    public function getAccountTypes(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
    {
        $query = $this->accountType->query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $paginate ? $query->paginate() : $query->get();
    }

    public function getAccountTypeById($accountType, ?array $relation = [], ?array $columns = [])
    {
        $query = $this->accountType->query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $query->find($accountType)->firstOrFail();
    }

    public function createAccountType(array $attribute)
    {
        try {
            $this->accountType->create($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed", 500, $e);
        }
    }

    public function updateAccountType($attribute, AccountType $accountType)
    {
        try {
            $accountType->update($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed", 500, $e);
        }
    }

    public function deleteAccountType($accountType)
    {
        try {
            return $accountType->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed", 500, $e);
        }
    }
}
