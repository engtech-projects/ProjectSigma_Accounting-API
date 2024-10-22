<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentRequestDetailsResource extends JsonResource
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
			'payment_request_id' => $this->payment_request_id,
			'account_id' => $this->account_id,
			'stakeholder_id' => $this->stakeholder_id,
			'stakeholder' => StakeholderResource::make($this->whenLoaded('stakeholder')),
			'particulars' => $this->particulars,
			'cost' => $this->cost,
			'vat' => $this->vat,
			'amount' => $this->amount
		];
    }
}
