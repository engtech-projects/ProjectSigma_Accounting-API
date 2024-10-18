<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalDetailsResource extends JsonResource
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
			'journal_entry_id' => $this->journal_entry_id,
			'account_id' => $this->account_id,
			'account' => AccountsResource::make($this->whenLoaded('account')),
			'stakeholder_id' => $this->stakeholder_id,
			'description' => $this->description,
			'stakeholder' => StakeholderResource::make($this->whenLoaded('stakeholder')),
			'debit' => $this->debit,
			'credit' => $this->credit,
		];
    }
}
