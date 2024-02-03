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

<<<<<<< Updated upstream
        $account = DB::transaction(function () use ($attribute) {
            return $this->account->create($attribute);
        });
        return $account;
=======
        return DB::transaction(function () use ($attribute) {
            $this->account->create($attribute);
        });
>>>>>>> Stashed changes

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
