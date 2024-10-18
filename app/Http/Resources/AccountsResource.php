<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'id' => $this->id,
			'account_type_id' => $this->account_type_id,
			'account_number' => $this->account_number,
			'account_name' => $this->account_name,
			'account_description' => $this->account_description,
			'bank_reconciliation' => $this->bank_reconciliation,
			'is_active' => $this->is_active,
			'statement' => $this->statement,
		];
    }
}
