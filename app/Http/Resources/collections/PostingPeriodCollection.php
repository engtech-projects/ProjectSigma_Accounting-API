<?php

namespace App\Http\Resources\collections;

use App\Http\Resources\resources\OpeningBalanceResource;
use App\Http\Resources\resources\PostingPeriodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostingPeriodCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public static $wrap = "posting_period";
    public function toArray(Request $request): array
    {
        /* return [
            "posting_period" => $this->collection
        ]; */
        /* $this->collection->transform(function ($postingPeriod) {
            return [
                "period_id" => $postingPeriod->period_id,
                "period_start" => $postingPeriod->period_start,
                "period_end" => $postingPeriod->period_end,
                "balance" => OpeningBalanceResource::collection($postingPeriod->opening_balance),
            ];
        });

        return [
            "posting_period" => $this->collection
        ];
 */

        /*         return [
                    "posting_periods" => $data
                ]; */
        /*  $this->collection->transform(function ($accountGroup) {
             return new PostingPeriodResource($accountGroup);
         }); */
        return parent::toArray($request);

    }
}
