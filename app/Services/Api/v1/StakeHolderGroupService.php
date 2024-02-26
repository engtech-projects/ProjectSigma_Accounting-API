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

        DB::transaction(function () use ($attributes) {
            return $this->stakeHolderGroup->create($attributes)->type_groups()->attach($attributes["stakeholder_type_id"]);
        });

    }


    public function update($stakeHolderGroup, array $attributes)
    {
        DB::transaction(function () use ($attributes, $stakeHolderGroup) {
            $stakeHolderGroup->fill($attributes)->update();
            $stakeHolderGroup->type_groups()
                ->sync($attributes["stakeholder_type_id"]);
        });
    }

}
