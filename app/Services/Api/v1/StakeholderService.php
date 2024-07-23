<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\StakeHolder;
use Exception;
use Illuminate\Support\Facades\DB;

class StakeholderService
{

    protected $stakeholder;
    public function __construct(StakeHolder $stakeholder)
    {
        $this->stakeholder = $stakeholder;
    }

    public function getAll()
    {
        return $this->stakeholder->all();
    }

    public function getById($stakeHolder)
    {
        return $stakeHolder;
    }

    public function create(array $attributes)
    {
        try {
            $this->stakeholder->create($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }

    }

    public function update($stakeholder, array $attributes)
    {
        try {
            $stakeholder->fill($attributes)->update();
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }

    }

    public function delete($stakeholder)
    {
        try {
            $stakeholder->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }
    }
}
