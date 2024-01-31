<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\resources\AccountTypeResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'account_description' => $this->account_description,
            'parent_account' => $this->parent_account,
            'status' => $this->status,
            'bank_reconciliation' => $this->bank_reconciliation,
            'statement' => $this->statement,
            'account_type_id' => $this->account_type_id,
            'account_type' => new AccountTypeResource($this->whenLoaded('type'))

        ];
    }
}
