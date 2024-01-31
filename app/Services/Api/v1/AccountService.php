<?php

namespace App\Services\Api\v1;

use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountService
{
    protected $account;
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function getAccounts(?array $relation = [], ?bool $paginate = true, ?array $columns = [])
    {
        $query = $this->account->query();
        if (!empty($relation)) {
            $query->withRelation($relation);
        }
        if (!empty($columns)) {
            $query->select($columns);
        }
        return $paginate ? $query->paginate() : $query->get();
    }
    public function getAccountById($account, ?array $relation = [])
    {
        $query = $this->account->query();
        if ($relation) {
            $query->with($relation);
        }
        return $query->find($account)->first();
    }
    public function createAccount(array $attribute)
    {
        try {
            $account = DB::transaction(function () use ($attribute) {
                return $this->account->create($attribute);
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $account;

    }
    public function updateAccount($account, array $data)
    {
        try {
            $this->account = $account;
            $account = DB::transaction(function () use ($data) {
                return $this->account->update($data);
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $account;

    }
    public function deleteAccount($account)
    {
        try {
            $account = DB::transaction(function () use ($account) {
                return $account->delete($account);
            });
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $account;


    }
}
