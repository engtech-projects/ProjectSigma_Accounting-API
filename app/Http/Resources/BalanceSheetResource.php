<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'account_type_id' => $this->account->account_type_id,
            'report_group_id' => $this->account->report_group_id,
            'account_type' => $this->account->account_type,
            'account_category' => $this->account->account_category,
            'account_name' => $this->account->account_name,
            'debit' => $this->debit ? (float) $this->debit : 0.00,
            'credit' => $this->credit ? (float) $this->credit : 0.00,
        ];
    }
}
