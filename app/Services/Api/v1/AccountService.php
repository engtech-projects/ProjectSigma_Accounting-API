<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\Account;
use App\Models\AccountType;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountService
{
    protected $account;
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function getAll(?array $relation = [], ?array $columns = [])
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
    public function getById($account, ?array $relation = [])
    {
        if ($relation) {
            $account->with($relation);
        }
        return $account;
    }
    public function create(array $attributes)
    {

        DB::transaction(function () use ($attributes) {
            $account = $this->account->create($attributes);
            $account->opening_balance()->create([
                'period_id' => 1,
                'opening_balance' => $attributes['opening_balance'],
                'remaining_balance' => $attributes['opening_balance'],
            ]);
            $account->account_has_group()->attach($attributes['account_group_id']);
        });
    }
    public function updateAccount($account, array $attributes)
    {

        DB::transaction(function () use ($attributes, $account) {
            $account->update($attributes);
            $account->opening_balance()->update([
                'period_id' => 1,
                'opening_balance' => $attributes['opening_balance'],
                'remaining_balance' => $attributes['opening_balance'],
            ]);
            $account->account_has_group()->sync($attributes['account_group_id']);
        });
    }
}
