<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'account_id' => $this->account_id,
            'account' => $this->account,
            'credit' => $this->credit,
            'remarks' => $this->description,
            'debit' => $this->debit,
            'stakeholder' => $this->stakeholder,
            'reference_no' => $this->journalEntry->voucher?->isEmpty() ? '-' : $this->journalEntry->voucher?->first()->voucher_no,
            'reference_series' => $this->journalEntry->voucher?->isEmpty() ? null : substr($this->journalEntry->voucher?->first()->voucher_no, strpos($this->journalEntry->voucher?->first()->voucher_no, '-') + 1),
            'voucher_date' => $this->journalEntry->voucher?->isEmpty() ? null : $this->journalEntry->voucher?->first()->date,
            'po_number' => '',
            'net_amount' => $this->journalEntry->voucher?->isEmpty() ? null : $this->journalEntry->voucher?->first()->net_amount,
            'terms' => '',
            'supplier' => '',
            'payees_name' => $this->JournalEntry->voucher?->isEmpty() ? null : $this->JournalEntry->voucher?->first()->stakeholder?->name,
            'project_department_name' => $this->stakeholder?->name,
            'location' => str_replace('App\\Models\\Stakeholders\\', '', $this->stakeholder?->stakeholdable_type),
            'manager' => '-',
            'status' => $this->journalEntry->status,
            'particulars' => $this->description,
        ];
    }
}
