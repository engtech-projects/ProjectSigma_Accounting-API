<?php

namespace App\Http\Resources\AccountingCollections;

use App\Enums\JournalStatus;
use App\Enums\ParticularsType;
use App\Http\Resources\JournalEntryDetailsResource;
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
            'journal_type' => $this->details->isEmpty() ? '-' : $this->voucher->first()?->book?->code,
            'date_filed' => $this->created_at_human,
            'created_by_user' => $this->created_by_user_name,
            'voucher_status' => $this->status === JournalStatus::OPEN->value ? 'for disbursement' : ($this->status === JournalStatus::UNPOSTED->value ? 'FOR CASH' : ($this->status === JournalStatus::FOR_PAYMENT->value ? 'For Payment' : null)),
            'status' => ucfirst($this->status),
            'balance' => $this->details->sum('debit'),
            'net_amount' => $this->details->sum('debit'),
            'total_amount_formatted' => number_format($this->details->sum('debit'), 2, '.', ','),
            'to_cash_details' => JournalEntryDetailsResource::collection($this->details->where('description', ParticularsType::ACCOUNTS_PAYABLE->value)),
            'details' => JournalEntryDetailsResource::collection($this->details),
        ];
    }
}
