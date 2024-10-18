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
			'id' => $this->id,
			'check_no' => $this->check_no,
			'voucher_no' => $this->voucher_no,
			'stakeholder_id' => $this->stakeholder_id,
			'stakeholder' => StakeholderResource::make($this->whenLoaded('stakeholder')),
			'particulars' => $this->particulars,
			'net_amount' => $this->net_amount,
			'amount_in_words' => $this->amount_in_words,
			'date_encoded' =>  $this->date_encoded,
			'voucher_date' =>  $this->voucher_date,
			'status' =>  $this->status,
			'account_id' => $this->account_id,
			'account' => AccountsResource::make($this->whenLoaded('account')),
			'book_id' => $this->book_id,
			'book' => BookResource::make($this->whenLoaded('book')),
			'details' => VoucherDetailsResource::collection($this->whenLoaded('details')),
		];
    }
}
