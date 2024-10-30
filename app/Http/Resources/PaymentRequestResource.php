<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentRequestResource extends JsonResource
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
			'stakeholder_id' => $this->stakeholder_id,
			'stakeholder' => StakeholderResource::make($this->whenLoaded('stakeholder')),
			'prf_no' => $this->prf_no,
			'request_date' => $this->request_date ,
			'description' => $this->description,
			'total' => $this->total,
			'form' => $this->form,
			'details' => PaymentRequestDetailsResource::collection($this->whenLoaded('details')),
		];
    }
}