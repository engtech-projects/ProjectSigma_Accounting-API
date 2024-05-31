<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "transaction_id" => $this->transaction_id,
            "transaction_no" => $this->transaction_no,
            "transaction_date" => $this->transaction_date,
            "status" => $this->status,
            "reference_no" => $this->reference_no,
            "transaction_type_id" => $this->transaction_type_id,
            "payee" => $this->whenLoaded('stakeholder', function () {
                return [
                    "full_name" => $this->stakeholder->fullname_last,
                ];
            }),
            "description" => $this->description,
            "note" => $this->note,
            "amount" => $this->amount,
            "transaction_details" => $this->whenLoaded('transaction_details', function ($details) {
                return TransactionDetailResource::collection($details);
            }),

        ];
    }
}
