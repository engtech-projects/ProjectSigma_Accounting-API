<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeHolderTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stakeholder_type_id' => $this->stakeholder_type_id,
            'stakeholder_type_name' => $this->stakeholder_type_name,
            'stakeholders' => $this->whenLoaded('stakeholders', function ($stakeholders) {
                return StakeholderResource::collection($stakeholders);
            })
        ];
        //return parent::toArray($request);
    }
}
