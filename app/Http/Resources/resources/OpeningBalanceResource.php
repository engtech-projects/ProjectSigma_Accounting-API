<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\AccountCollections;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpeningBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "balance_id" => $this->balance_id,
            "opening_balance" => $this->opening_balance,
            "remaining_balance" => $this->remaining_balance,
            "posting_period" => new PostingPeriodResource($this->whenLoaded('posting_period')),
            "account" => AccountResource::collection($this->whenLoaded("account")),
        ];
        //return parent::toArray($request);
    }
}
