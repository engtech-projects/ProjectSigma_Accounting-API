<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Exceptions\ResourceNotFound;
use App\Models\Transaction;
use App\Models\TransactionType;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
        if ($filters['status']) {
            $query->where('status', $filters['status']);
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

    public function createTransaction(array $attributes)
    {
        $transactionTypeId = $attributes['transaction_type_id'];
        try {

            DB::transaction(function () use ($attributes) {
                $transaction = Transaction::create($attributes);
                $transaction->transaction_details()->createMany($attributes["details"]);
            });
        } catch (DBTransactionException $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        } catch (ResourceNotFound $e) {
            throw new ResourceNotFound($e->getMessage(), 422, $e);
        }
    }

    public function updateTransaction($transactionType, array $attribute)
    {
    }

    public function deleteTransaction($transactionType)
    {
    }
}
