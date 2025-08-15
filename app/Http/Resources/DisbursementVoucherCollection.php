<?php

namespace App\Http\Resources;

use App\Http\Resources\AccountingCollections\JournalEntryCollection;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Http\Resources\AccountingCollections\StakeholderCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisbursementVoucherCollection extends JsonResource
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
