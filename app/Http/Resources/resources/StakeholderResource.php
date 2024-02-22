<?php

namespace App\Http\Resources\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StakeholderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "stakeholder_id" => $this->stakeholder_id,
            "stakeholder_name" => $this->stakeholder_name,
            "stakeholder_type" => $this->stakeholder_type,
            "stakeholder_group" => $this->stakeholder_group,
            "email" => $this->email,
            "company" => $this->company,
            "display_name" => $this->display_name,
            "street" => $this->street,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "phone_number" => $this->phone_number,
            "mobile_number" => $this->mobile_number,

        ];
        //return parent::toArray($request);
    }
}
