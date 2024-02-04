<?php

namespace App\Services\Api\v1;

use App\Models\Account;
use App\Models\AccountCategory;
use Illuminate\Support\Facades\DB;

class AccountService
{
    protected $account;
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function getAccountList(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
    {
        $query = $this->account->query()->active();
        if ($relation) {
            $query->with($relation);
        }
        if (!empty($columns)) {
            $query->select($columns);
        }
        return $paginate ? $query->paginate() : $query->get();
    }
    public function getAccountWithSubAccount(bool $paginate = true)
    {

        $query = AccountCategory::query()->with([
            'account_type:type_id,type_number,type_name,category_id',
            'account_type.account'
        ]);
        return $paginate ? $query->paginate(10) : $query->get();
    }
    public function getAccountById(Account $account, ?array $relation = [])
    {
        $query = $account->query();
        if ($relation) {
            $query->with($relation);
        }
        return $query->find($account)->firstOrFail();
    }
    public function createAccount(array $attribute)
    {

        return DB::transaction(function () use ($attribute) {
            $this->account->create($attribute);
        });

    }
    public function updateAccount($account, array $data)
    {
        return DB::transaction(function () use ($data) {
            $this->account->update($data);
        });

    }
    public function deleteAccount($account)
    {
        return DB::transaction(function () use ($account) {
            return $account->delete($account);
        });
    }
}
