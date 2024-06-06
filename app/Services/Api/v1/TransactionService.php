<?php

namespace App\Services\Api\v1;

use App\Models\Transaction;
use App\Models\TransactionType;

class TransactionService
{

    protected $transaction;
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getAll(?array $relation = [], $filters = [])
    {
        $query = Transaction::query();
        if ($filters['transaction_type']) {
            $transactionType = TransactionType::where('transaction_type_name', $filters['transaction_type'])->first();
            if ($transactionType) {
                $query = $query->where('transaction_type_id', $transactionType->transaction_type_id);
            }
        }
        return $query->with($relation)->get();
    }


    public function getTransactionById(Transaction $transaction, array $relation = [])
    {
        if ($relation) {
            $transaction = $transaction->load($relation);
        }
        return $transaction;
    }

    public function createTransaction(array $attribute)
    {
    }

    public function updateTransaction($transactionType, array $attribute)
    {
    }

    public function deleteTransaction($transactionType)
    {
    }
}
