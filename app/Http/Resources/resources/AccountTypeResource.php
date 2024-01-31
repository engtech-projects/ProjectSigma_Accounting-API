<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'id'=> $this->id,
            'account_type_number' => $this->account_type_number,
            'type' => $this->account_type,
            'has_opening_balance' => $this->account_type_name,
            'account_category_id' => $this->account_category_id,
            'account_category' => $this->whenLoaded('account_category')
        ];
    }
}
