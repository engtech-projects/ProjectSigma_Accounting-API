<?php

namespace App\Http\Resources;

use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashVoucherResource extends JsonResource
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
            'payment_request' => PaymentRequestCollection::make($this->journalEntry->paymentRequest),
            'step_approval' => [
                'payment_request' => [
                    'title' => 'Payment Request Approval',
                    'data'  => $this->journalEntry->paymentRequest()?->first()->approvals ?? []
                ],
                'disbursement_voucher' => [
                    'title' => 'Disbursement Voucher Approval',
                    'data'  => $this->journalEntry?->voucher()?->first()->approvals ?? []
                ],
                'cash_voucher' => [
                    'title' => 'Cash Voucher Approval',
                    'data'  => $this->approvals ?? []
                ],
            ]
        ];
    }
}
