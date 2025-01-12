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
            ...parent::toArray($request),
            'stakeholder' => StakeholderResource::make($this->whenLoaded('stakeholder')),
            'account' => AccountsResource::make($this->whenLoaded('account')),
            'book' => BookResource::make($this->whenLoaded('book')),
            'details' => VoucherDetailsResource::collection($this->whenLoaded('details')),
            'approvals' => new ApprovalAttributeResource(['approvals' => $this->approvals]),
            'date_filed' => $this->created_at_human,
            'next_approval' => $this->getNextPendingApproval(),
            'journal_entry' => JournalEntryResource::make($this->whenLoaded('journalEntry')),
        ];
    }
}
