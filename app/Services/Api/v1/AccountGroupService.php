<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\AccountGroup;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountGroupService
{
    public function getAll(?bool $paginate = false, ?array $relation = [])
    {
        $query = AccountGroup::query();
        if ($relation) {
            $query->with($relation);
        }
        return $paginate ? $query->paginate(10) : $query->get();
    }

    public function getById(AccountGroup $accountGroup, ?array $relation = [])
    {
        if ($relation) {
            $accountGroup->load($relation);
        }
        return $accountGroup;
    }

    public static function create(array $attributes)
    {
        try {
            DB::transaction(function () use ($attributes) {
                $accountGroup = AccountGroup::create($attributes);
                $accountGroup->accounts()->attach($attributes["account_ids"]);
            });
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", $e);
        }
    }

    public function update($accountGroup, array $attributes)
    {
        DB::transaction(function () use ($accountGroup, $attributes) {
            $accountGroup = $accountGroup->fill($attributes);
            $accountGroup->accounts()->sync($attributes['account_ids']);
            $accountGroup->update();
        });
    }

    public function deleteAccountGroup($accountGroup)
    {
        try {
            $accountGroup->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", $e);
        }
    }
}
