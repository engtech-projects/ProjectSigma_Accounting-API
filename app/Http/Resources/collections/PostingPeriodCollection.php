<?php

namespace App\Http\Resources\collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostingPeriodCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public static $wrap = "posting_periods";
    public function toArray(Request $request): array
    {
        /* return [
            "data" => $this->collection
        ]; */

        return parent::toArray($request);
    }
}
