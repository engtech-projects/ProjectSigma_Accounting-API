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
            'voucher_status' => $this->status === JournalStatus::OPEN->value ? 'for disbursement' : ($this->status === JournalStatus::UNPOSTED->value ? 'FOR CASH' : null),
            'status' => ucfirst($this->status),
            'balance' => $this->details->sum('debit') - $this->details->sum('credit'),
            'net_amount' => $this->details->sum('debit'),
            'details' => $this->details->map(function ($detail) {
                return [
                    'account_id' => $detail->account_id,
                    'account' => $detail->account,
                    'credit' => $detail->credit,
                    'remarks' => $detail->description,
                    'debit' => $detail->debit,
                    'stakeholder' => $detail->stakeholder,
                    'journal_type' => $this->voucher->isEmpty() ? '-' : $this->voucher->first()->book->code,
                    'reference_no' => $this->voucher->isEmpty() ? '-' : $this->voucher->first()->voucher_no,
                    'reference_series' => $this->voucher->isEmpty() ? null : substr($this->voucher->first()->voucher_no, strpos($this->voucher->first()->voucher_no, '-') + 1),
                    'voucher_date' => $this->voucher->isEmpty() ? null : $this->voucher->first()->date,
                    'po_number' => '',
                    'net_amount' => $this->voucher->isEmpty() ? null : $this->voucher->first()->net_amount,
                    'terms' => '',
                    'supplier' => '',
                    'payees_name' => $this->payment_request?->stakeholder?->name,
                    'project_department_name' => $detail->stakeholder?->name,
                    'location' => str_replace('App\\Models\\Stakeholders\\', '', $detail->stakeholder?->stakeholdable_type),
                    'manager' => '-',
                    'status' => $this->status,
                    'particulars' => $detail->description,
                ];
            }),
        ];
    }
}
