<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\StakeHolderGroup;
use Exception;

class StakeHolderGroupService
{
    protected $stakeHolderGroup;
    public function __construct(StakeHolderGroup $stakeHolderGroup)
    {
        $this->stakeHolderGroup = $stakeHolderGroup;
    }

    public function getAll()
    {
        $query = $this->stakeHolderGroup->query();

        return $query->get();
    }

    public function getById($stakeHolderGroup)
    {
        return $stakeHolderGroup;
    }

    public function create(array $attributes)
    {
        try {
            return $this->stakeHolderGroup->create($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }

    }


    public function update($stakeHolderGroup, array $attributes): bool
    {
        try {
            $stakeHolderGroup = $stakeHolderGroup->fill($attributes)->update();
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }

        return $stakeHolderGroup;
    }

    public static function delete($stakeHolderGroup): bool
    {
        try {
            $stakeHolderGroup = $stakeHolderGroup->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }

        return $stakeHolderGroup;

    }
}
