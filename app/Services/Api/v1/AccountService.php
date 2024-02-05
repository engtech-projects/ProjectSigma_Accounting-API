<?php

namespace App\Services\Api\v1;

use App\Models\Account;
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
        $query = $this->account->query()->activeAccount();
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

        $query = Account::query();
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
            return $this->account->create($attribute)
                ->account_balance()->create([
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
            $account->account_balance()->update([
                'period_id' => 1,
                'opening_balance' => $attribute['opening_balance'],
                'remaining_balance' => $attribute['opening_balance'],
            ]);
            return $account;
        });

    }
    public function deleteAccount($account)
    {
        return DB::transaction(function () use ($account) {
            return $account->delete($account);
        });
    }
}
