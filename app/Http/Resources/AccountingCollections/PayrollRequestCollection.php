<?php

namespace App\Http\Resources\AccountingCollections;

use App\Http\Resources\ApprovalAttributeCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// use App\Http\Resources\StakeholderResource;
// use App\Http\Resources\FormResource;

class PayrollRequestCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'date_filed' => $this->created_at_human,
            'created_by_user' => $this->created_by_user_name,
            'approvals' => new ApprovalAttributeCollection(['approvals' => $this?->approvals]),
            'total_amount_formatted' => number_format($this->total, 2, '.', ','),
        ]);
    }
}
