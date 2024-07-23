<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\StakeHolderType;
use Exception;

class StakeHolderTypeService
{

    protected $stakeHolderType;

    public function __construct(StakeHolderType $stakeHolderType)
    {
        $this->stakeHolderType = $stakeHolderType;
    }
    public function getAll()
    {
        return $this->stakeHolderType->all();
    }

    public function getById(StakeHolderType $stakeHolderType)
    {
        return $stakeHolderType;
    }

    public function create(array $attributes)
    {
        try {
            $this->stakeHolderType->create($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }
    }

    public function update($stakeHolderType, array $attributes)
    {
        try {
            $stakeHolderType->fill($attributes)->update();
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }
    }

    public function delete($stakeHolderType)
    {
        try {
            $stakeHolderType->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }
    }
}
