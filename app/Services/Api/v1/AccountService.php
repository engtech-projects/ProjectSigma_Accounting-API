<?php

namespace App\Services\Api\v1;

use App\Models\Account;
use App\Models\AccountCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountService
{
    protected $account;
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function getAccounts(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
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
            'account_type:id,account_type_number,account_type,account_category_id',
            'account_type.account' => function ($query) {
                $query->parentAccount()->with('sub_account');
            },
        ]);
        return $paginate ? $query->paginate(10) : $query->get();
    }
    public function getAccountById($account, ?array $relation = [])
    {
        $query = $this->account->query();
        if ($relation) {
            $query->with($relation);
        }
        return $query->find($account)->firstOrFail();
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
