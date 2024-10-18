<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryResource extends JsonResource
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
			'journal_no' => $this->journal_no,
			'journal_date' => $this->journal_date,
			'voucher_id' => $this->voucher_id,
			'voucher' => VoucherResource::make($this->whenLoaded('voucher')),
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'posting_period_id' => $this->posting_period_id,
			'period_id' => $this->period_id,
			'remarks' => $this->remarks,
			'reference_no' => $this->reference_no,
			'details' => JournalDetailsResource::collection($this->whenLoaded('details')),
		];
    }
}
