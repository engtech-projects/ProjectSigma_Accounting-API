<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubGroupCollection extends ResourceCollection
{
     /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = SubGroupResource::class;
    public function toArray($request)
    {
        return [
            'data' =>$this->collection,
        ];
    }
}
