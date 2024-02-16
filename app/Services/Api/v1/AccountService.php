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
        return $query->find($account)->first();
    }
    public function createAccount(array $attribute)
    {
        try {
            DB::transaction(function () use ($attribute) {
                $this->account->create($attribute)
                    ->opening_balance()->create([
                            'period_id' => 1,
                            'opening_balance' => $attribute['opening_balance'],
                            'remaining_balance' => $attribute['opening_balance'],
                        ]);
            });
        } catch (Exception $e) {
            throw new DBTransactionException('Create transaction failed.', 500, $e);
        }
    }
    public function updateAccount($account, array $attribute)
    {
        try {
            DB::transaction(function () use ($attribute, $account) {
                $account->update($attribute);
                $account->opening_balance()->update([
                    'period_id' => 1,
                    'opening_balance' => $attribute['opening_balance'],
                    'remaining_balance' => $attribute['opening_balance'],
                ]);
            });
        } catch (Exception $e) {
            throw new DBTransactionException('Update transaction failed.', 500, $e);
        }

    }
    public function deleteAccount($account)
    {
        try {
            $account->delete();
        } catch (Exception $e) {
            throw new DBTransactionException('Create transaction failed.', 500, $e);
        }
    }
}
