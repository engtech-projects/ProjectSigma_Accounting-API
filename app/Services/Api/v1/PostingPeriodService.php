<?php

namespace App\Services\Api\v1;

use App\Models\PostingPeriod;
use Illuminate\Support\Facades\DB;

class PostingPeriodService
{

    protected $postingPeriod;
    public function __construct(PostingPeriod $postingPeriod)
    {
        $this->postingPeriod = $postingPeriod;
    }

    public function getPostingPeriodList(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
    {
        $query = $this->postingPeriod->query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }

        return $paginate ? $query->paginate(10) : $query->get();

    }

    public function getPostingPeriod($postingPeriod)
    {
        return $postingPeriod;
    }

    public function createPostingPeriod(array $attribute)
    {
        return DB::transaction(function () use ($attribute) {
            return $this->postingPeriod->create($attribute);
        });
    }

    public function updatePostingPeriod($postingPeriod, array $attribute)
    {
        return DB::transaction(function () use ($postingPeriod, $attribute) {
            return $postingPeriod->update($attribute);
        });

    }

    public function deletePostingPeriod($postingPeriod)
    {
        return DB::transaction(function () use ($postingPeriod) {
            return $postingPeriod->delete();
        });

    }


}
