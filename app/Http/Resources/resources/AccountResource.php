<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\OpeningBalanceCollection;
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


    public static $wrap = "accounts";
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'account_id' => $this->account_id,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'account_description' => $this->account_description,
            'status' => $this->status,
            'bank_reconciliation' => $this->bank_reconciliation,
            'statement' => $this->statement,
            'type_id' => $this->type_id,
            'account_type' => new AccountTypeResource($this->whenLoaded('account_type')),
            "opening_balance" => new OpeningBalanceResource($this->whenLoaded('opening_balance', function () {
                return $this->opening_balance->first();
            }))

        ];
    }
}
