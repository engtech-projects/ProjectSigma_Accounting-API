<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\collections\BookCollection;
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
            'book' => new BookResource($this->whenLoaded('book')),
            'account' => new AccountResource($this->whenLoaded('accounts')),
            'stakeholder_group' => $this->whenLoaded('stakeholder_group', function () {
                /*                 return $value; */
                /*               dd($this->stakeholder_group); */
                return [
                    "stakeholder_group_id" => $this->stakeholder_group_id,
                    "stakeholder_group_name" => $this->stakeholder_group_name,
                    "stakeholder_type" => $this->whenLoaded('stakeholder_group', function () {
                        return $this->stakeholder_group->type_groups;
                    }),
                ];
            }),
        ];
        //return parent::toArray($request);
    }
}
