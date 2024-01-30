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
    public function getAccounts()
    {
        return $this->account->with(['account_type.account_category'])->get();
    }
    public function getAccountById($account)
    {
        return $this->account->find($account);
    }
    public function createAccount(array $data)
    {
        try {
            $account = DB::transaction(function () use ($data) {
                return $this->account->create($data);
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
