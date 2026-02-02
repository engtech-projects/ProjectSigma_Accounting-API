<?php

namespace App\Http\Resources\AccountingCollections;

use App\Http\Resources\AccountCollection;
use App\Http\Resources\ApprovalAttributeCollection;
use App\Http\Resources\VoucherDetailsCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherCollection extends JsonResource
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
            'stakeholder' => StakeholderCollection::make($this->whenLoaded('stakeholder')),
            'account' => AccountCollection::make($this->whenLoaded('account')),
            'book' => BookCollection::make($this->whenLoaded('book')),
            'details' => VoucherDetailsCollection::collection($this->whenLoaded('details')),
            'approvals' => new ApprovalAttributeCollection(['approvals' => $this->approvals]),
            'date_filed' => $this->created_at_human,
            'next_approval' => $this->getNextPendingApproval(),
            'journal_entry' => JournalEntryCollection::make($this->whenLoaded('journalEntry')),
            'payment_request' => PaymentRequestCollection::make($this->journalEntry->paymentRequest),
        ];
    }
}
