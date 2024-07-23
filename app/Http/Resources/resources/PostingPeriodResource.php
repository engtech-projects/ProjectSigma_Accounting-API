<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\OpeningBalanceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostingPeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = "posting_period";
    public function toArray(Request $request): array
    {
        return [
            "period_id" => $this->period_id,
            "period_start" => $this->period_start->format('Y-m-d'),
            "period_end" => $this->period_end->format('Y-m-d'),
            "status" => $this->status,
            "opening_balance" => $this->opening_balance,
        ];
        //return parent::toArray($request);
    }
}
