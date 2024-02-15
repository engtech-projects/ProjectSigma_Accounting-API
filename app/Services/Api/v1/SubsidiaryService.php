<?php

namespace App\Services\Api\v1;

use App\Models\Subsidiary;
use Illuminate\Support\Facades\DB;

class SubsidiaryService
{
    protected $subsidiary;
    public function __construct(Subsidiary $subsidiary)
    {
        $this->subsidiary = $subsidiary;
    }

    public function getSubsidiaryList()
    {
        return Subsidiary::all();

    }

    public function getSubsidiaryById(Subsidiary $subsidiary)
    {
        return $subsidiary;

    }

    public function createSubsidiary(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $this->subsidiary->create($attributes);
        });
    }

    public function updateSubsidiary(Subsidiary $subsidiary, array $attributes)
    {
        return DB::transaction(function () use ($subsidiary, $attributes) {
            $subsidiary->update($attributes);
        });

    }

    public function deleteSubsidiary(Subsidiary $subsidiary)
    {
        return DB::transaction(function () use ($subsidiary) {
            $subsidiary->delete();
        });

    }
}
