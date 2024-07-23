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
            "fullname_first" => $this->fullname_first,
            "fullname_last" => $this->fullname_last,
            "firstname" => $this->firstname,
            "middlename" => $this->middlename,
            "lastname" => $this->lastname,
            "stakeholder_type" => $this->whenLoaded('stakeholder_type'),
            "stakeholder_group" => $this->whenLoaded('stakeholder_group'),
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
