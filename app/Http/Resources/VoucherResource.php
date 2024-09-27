<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'voucher_no' => $this->voucher_no,
			'payee' => $this->payee,
			'particulars' => $this-> particulars,
			'net_amount' => $this->net_amount,
			'amount_in_words' => $this->amount_in_words,
			'date_encoded' =>  $this->date_encoded,
			'voucher_date' =>  $this->voucher_date,
			'status' =>  $this->status,
			'items' => $this->items
		];
    }
}
