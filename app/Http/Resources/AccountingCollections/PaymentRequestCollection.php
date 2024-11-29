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
        return [
            ...parent::toArray($request),
            'date_filed' => $this->created_at_human,
            'created_by_user' => $this->created_by_user_name,
            'approvals' => new ApprovalAttributeResource(['approvals' => $this->approvals]),
            'next_approval' => $this->getNextPendingApproval(),
        ];
    }
}
