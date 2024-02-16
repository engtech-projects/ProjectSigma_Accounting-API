<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\TransactionType;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionTypeService
{

    protected $transactionType;
    public function __construct(TransactionType $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    public function getTransactionTypeList(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
    {
        $query = $this->transactionType::query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }

        return $paginate ? $query->paginate() : $query->get();
    }

    public function getTransactionTypeById($transactionType, ?array $relation = [], ?array $columns = [])
    {
        $query = $transactionType::query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $query->find($transactionType)->firstOrFail();

    }

    public function createTransactionType(array $attribute)
    {
        try {
            $this->transactionType->create($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }
    }

    public function updateTransactionType($transactionType, array $attribute)
    {
        try {
            $transactionType->update($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }

    }
    public function deleteTransactionType($transactionType)
    {
        try {
            $transactionType->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }
    }
}
