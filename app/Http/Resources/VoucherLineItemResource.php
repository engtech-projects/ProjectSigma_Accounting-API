<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherLineItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'voucher_id' => $this->voucher_id,
			'account_id' => $this->account_id,
			'account_code' => $this->account()->account_code,
			'account_name' => $this->account()->account_name,
			'contact' => $this->contact,
			'debit' => $this->debit,
			'credit' -> $this->credit,
		];
    }
}
