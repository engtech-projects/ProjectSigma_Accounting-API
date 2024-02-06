<?php

namespace App\Services\Api\V1;

use App\Models\PostingPeriod;

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
        if($relation) {
            $query->with($relation);
        }
        if($columns) {
            $query->select($columns);
        }

        return $paginate ? $query->paginate(10) : $query->get();

    }

    public function getPostingPeriod($postingPeriod)
    {

    }

    public function createPostingPeriod(array $attribute)
    {

    }

    public function updatePostingPeriod(array $attribute)
    {

    }

    public function deletePostingPeriod($postingPeriod)
    {

    }


}
