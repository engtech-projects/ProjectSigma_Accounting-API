<?php

namespace App\Http\Resources\AccountingCollections;

use App\Enums\JournalStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'date_filed' => $this->created_at_human,
            'created_by_user' => $this->created_by_user_name,
            'voucher_status' => $this->status === JournalStatus::POSTED->value ? 'for disbursement release' : null,
        ];
    }
}
