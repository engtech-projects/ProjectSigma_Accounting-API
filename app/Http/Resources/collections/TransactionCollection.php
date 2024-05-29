<?php

namespace App\Http\Resources\collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* return parent::toArray($request); */

        $data = $this->map(function ($value, $index) {

            return [
                "transaction_id" => $value->transaction_id,
                "transaction_no" => $value->transaction_no,
                "transaction_date" => $value->transaction_date,
                "status" => $value->status,
                "reference_no" => $value->reference_no,
                "transaction_type_id" => $value->transaction_type_id,
                "period_id" => $value->period_id,
                "stakeholder" => $value->stakeholder,
                "description" => $value->description,
                "note" => $value->note,
                "amount" => $value->amount,
            ];
        });
        return $data->toArray();
    }
}
