<?php

namespace App\Services\Api\v1;

use App\Models\TransactionType;
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
        return DB::transaction(function () use ($attribute) {
            $this->transactionType->create($attribute);
        });

    }

    public function updateTransactionType($transactionType, array $attribute)
    {
        return DB::transaction(function () use ($transactionType, $attribute) {
            $transactionType->update($attribute);
        });

    }

    public function deleteTransactionType($transactionType)
    {
        return DB::transaction(function () use ($transactionType) {
            $transactionType->delete();
        });

    }
}
