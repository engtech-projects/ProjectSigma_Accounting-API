<?php

namespace App\Http\Resources\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookBalanceReportCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'account_id' => $this->account_id,
            'account_name' => $this->account_name,
            'account_type_id' => $this->account_type_id,
            'account_type' => $this->account_type,
            'opening_balance' => $this->opening_balance,
            'debit' => $this->debit,
            'credit' => $this->credit,
           'closing_balance' => $this->closing_balance,
        ];
    }
}
