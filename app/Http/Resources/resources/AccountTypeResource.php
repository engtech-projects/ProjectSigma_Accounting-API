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
            'account_type' => $this->account_type,
            'account_category' => $this->account_category,
            'balance_type' => $this->balance_type,
            'notation' => $this->notation,
        ];
    }
}
