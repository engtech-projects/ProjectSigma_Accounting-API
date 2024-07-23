<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\Subsidiary;
use Exception;
use Illuminate\Support\Facades\DB;

class SubsidiaryService
{
    protected $subsidiary;
    public function __construct(Subsidiary $subsidiary)
    {
        $this->subsidiary = $subsidiary;
    }

    public function getAll()
    {
        return Subsidiary::all();

    }

    public function getById(Subsidiary $subsidiary)
    {
        return $subsidiary;

    }

    public function createSubsidiary(array $attributes)
    {
        try {
            $this->subsidiary->create($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction Failed", 500, $e);
        }
    }

    public function updateSubsidiary(Subsidiary $subsidiary, array $attributes)
    {
        try {
            $subsidiary->update($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }

    }

    public function deleteSubsidiary(Subsidiary $subsidiary)
    {
        try {
            $subsidiary->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }
    }
}
