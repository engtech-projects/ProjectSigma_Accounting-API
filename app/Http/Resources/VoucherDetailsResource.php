<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherDetailsResource extends JsonResource
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
			'account' => $this->account_id,
			'stakeholder' => $this->stakeholder,
			'debit' => $this->debit,
			'credit' -> $this->credit,
		];
    }
}
