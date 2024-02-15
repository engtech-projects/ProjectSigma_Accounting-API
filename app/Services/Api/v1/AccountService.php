<?php

namespace App\Services\Api\v1;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Support\Facades\DB;

class AccountService
{
    protected $account;
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function getAccountList(?array $relation = [], ?array $columns = [])
    {
        $query = $this->account->query()->activeAccount();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $query->get();
    }

    public function chartOfAccounts()
    {
        return Account::with([
            'account_type',
            'opening_balance'
        ])->get();

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
            $this->account->create($attribute)
                ->opening_balance()->create([
                        'period_id' => 1,
                        'opening_balance' => $attribute['opening_balance'],
                        'remaining_balance' => $attribute['opening_balance'],
                    ]);
        });

    }
    public function updateAccount($account, array $attribute)
    {
        return DB::transaction(function () use ($attribute, $account) {
            $account->update($attribute);
            $account->opening_balance()->update([
                'period_id' => 1,
                'opening_balance' => $attribute['opening_balance'],
                'remaining_balance' => $attribute['opening_balance'],
            ]);
        });

    }
    public function deleteAccount($account)
    {
        return DB::transaction(function () use ($account) {
            $account->delete();
        });
    }
}
