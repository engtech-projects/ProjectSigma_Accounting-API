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
            'type_id'=> $this->type_id,
            'type_number' => $this->account_type_number,
            'type_name' => $this->account_type,
            'has_opening_balance' => $this->has_opening_balance,
            'category_id' => $this->category_id,
            'account_category' => $this->whenLoaded('account_category')
        ];
    }
}
