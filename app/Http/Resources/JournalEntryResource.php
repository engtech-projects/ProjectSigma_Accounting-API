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
            ...parent::toArray($request),
            'voucher' => VoucherResource::make($this->whenLoaded('voucher')),
            'details' => JournalDetailsResource::collection($this->whenLoaded('details')),
            'date_filed' => $this->created_at_human,
            'created_by_user' => $this->created_by_user_name,
        ];
    }
}
