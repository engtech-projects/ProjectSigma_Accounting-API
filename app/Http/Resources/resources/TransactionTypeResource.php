<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\collections\BookCollection;
use App\Models\StakeHolderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'transaction_type_id' => $this->transaction_type_id,
            'transaction_type_name' => $this->transaction_type_name,
            'symbol' => $this->symbol,
            'book' => $this->whenLoaded('book', function () {
                return new BookResource($this->book);
            }),
            'stakeholder_group' => $this->whenLoaded('stakeholder_group', function () {
                return [
                    "stakeholder_group_id" => $this->stakeholder_group_id,
                    "stakeholder_group_name" => $this->stakeholder_group->stakeholder_group_name,
                    "stakeholder_type" => $this->whenLoaded('stakeholder_group', function ($stakeholderGroup) {
                        return StakeholderResource::collection($stakeholderGroup->type_groups);
                    }),
                ];
            }),
        ];
    }
}
