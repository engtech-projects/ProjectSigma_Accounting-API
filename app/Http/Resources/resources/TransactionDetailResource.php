<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
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
            'transaction_detail_id' => $this->transaction_detail_id,
            'transaction_id' => $this->transaction_id,
            'stakeholder_group' => $this->whenLoaded('stakeholder_group'),
            'debit' => $this->debit,
            'credit' => $this->credit
        ];
    }
}
