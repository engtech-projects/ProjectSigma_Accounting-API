<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
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
            'stakeholder_id' => $this->stakeholder_id,
            'status' => $this->status,
            'formable_type' => $this->formable_type,
            'formable_id' => $this->formable_id,
        ];
    }
}
