<?php

namespace App\Services\Api\V1;

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
        if (!empty($relation)) {
            $query->withRelation($relation);
        }
        if (!empty($columns)) {
            $query->select($columns);
        }
        return $paginate ? $query->paginate() : $query->get();
    }

    public function getAccountTypeById(AccountType $accountType, ?array $relation = [])
    {
        $query = $accountType->query();
        if (!empty($relation)) {
            $query->with($relation);
        }
        return $query->find($accountType)->first();
    }

    public function createAccountType(array $attribute)
    {
        try {
            $accountType = DB::transaction(function () use ($attribute) {
                return $this->accountType->create($attribute);
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $accountType;

    }

    public function updateAccountType($attribute, AccountType $accountType)
    {
        try {
            $accountType = DB::transaction(function () use ($attribute, $accountType) {
                return $accountType->update($attribute);
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $accountType;
    }

    public function deleteAccountType($accountType)
    {
        try {
            $accountType = DB::transaction(function () use ($accountType) {
                return $accountType->delete($accountType);
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $accountType;

    }
}
