<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeHolderGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stakeholder_group_id' => $this->stakeholder_group_id,
            'stakeholder_group_name' => $this->stakeholder_group_name,
            'stakeholder_type' => $this->whenLoaded('type_groups', function ($type) {
                return StakeHolderTypeResource::collection($type);
            }),
        ];
    }
}
