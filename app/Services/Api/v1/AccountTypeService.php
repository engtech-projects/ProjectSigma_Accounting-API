<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\AccountType;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountTypeService
{
    protected $accountType;
    public function __construct(AccountType $accountType)
    {
        $this->accountType = $accountType;
    }
    public function getAll(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
    {
        $query = $this->accountType->query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $paginate ? $query->paginate() : $query->get();
    }

    public function getById($accountType, ?array $relation = [], ?array $columns = [])
    {
        $query = $this->accountType->query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $query->find($accountType)->first();
    }




}
