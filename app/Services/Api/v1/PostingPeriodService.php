<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\PostingPeriod;
use Exception;
use Illuminate\Support\Facades\DB;

class PostingPeriodService
{

    protected $postingPeriod;
    public function __construct(PostingPeriod $postingPeriod)
    {
        $this->postingPeriod = $postingPeriod;
    }

    public function getAll(bool $paginate = false, ?array $relation = [], )
    {
        $query = $this->postingPeriod->query();
        if ($relation) {
            $query->with($relation);
        }
        return $paginate ? $query->paginate(10) : $query->get();

    }

    public function getById(PostingPeriod $postingPeriod)
    {
        return $postingPeriod->with('opening_balance')->first();
    }

    public function createPostingPeriod(array $attribute)
    {
        try {
            $this->postingPeriod->create($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }

    }

    public function updatePostingPeriod($postingPeriod, array $attribute)
    {
        try {
            $postingPeriod->update($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }

    }

    public function deletePostingPeriod($postingPeriod)
    {
        try {
            $postingPeriod->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }

    }


}
