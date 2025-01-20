<?php

namespace App\Http\Resources\AccountingCollections;

use App\Http\Resources\ApprovalAttributeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// use App\Http\Resources\StakeholderResource;
// use App\Http\Resources\FormResource;

class PaymentRequestCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $details = $this->details->map(function ($detail) {
            $accountDetail = explode('::', $detail->particulars);

            return array_merge($detail->toArray(), [
                'account_id' => $accountDetail[2] ?? null,
                'stakeholder_name' => $accountDetail[3] ?? null,
                'stakeholder_id' => $accountDetail[4] ?? null,
                'particulars' => $accountDetail[0] ?? null,
            ]);
        });

        return array_merge(parent::toArray($request), [
            'date_filed' => $this->created_at_human,
            'created_by_user' => $this->created_by_user_name,
            'approvals' => new ApprovalAttributeResource(['approvals' => $this?->approvals]),
            'next_approval' => $this->getNextPendingApproval(),
            'details' => $details,
            'step_approval' => [
                'payment_request' => [
                    'title' => 'Payment Request Approval',
                    'details' => $this?->approvals,
                ],
                'disbursement_voucher' => [
                    'title' => 'Disbursement Voucher Approval',
                    'details' => $this->journalEntry->first()?->voucher()->first()->approvals ?? [],
                ],
                'cash_voucher' => [
                    'title' => 'Cash Voucher Approval',
                    'details' => $this->journalEntry->count() > 1 ? $this->journalEntry->last()?->voucher()->first()->approvals : [],
                ],
            ]
        ]);
    }
}
