<?php

namespace App\Services\Api\v1;

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
        return DB::transaction(function () use ($attribute) {
            $this->accountType->create($attribute);
        });


    }

    public function updateAccountType($attribute, AccountType $accountType)
    {

        return DB::transaction(function () use ($attribute, $accountType) {
            $accountType->update($attribute);
        });


    }

    public function deleteAccountType($accountType)
    {
        return DB::transaction(function () use ($accountType) {
            return $accountType->delete($accountType);
        });


    }
}
