<?php

namespace App\Services\Api\v1;

use Exception;
use App\Models\StakeHolderGroup;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DBTransactionException;

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
            DB::transaction(function () use ($attributes) {
                return $this->stakeHolderGroup->create($attributes)->type_groups()->attach($attributes["stakeholder_type_id"]);
            });

        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }

    }


    public function update($stakeHolderGroup, array $attributes)
    {
        try {
            DB::transaction(function () use ($attributes, $stakeHolderGroup) {
                $stakeHolderGroup->fill($attributes)->update();
                $stakeHolderGroup->type_groups()->sync($attributes["stakeholder_type_id"]);
            });
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
