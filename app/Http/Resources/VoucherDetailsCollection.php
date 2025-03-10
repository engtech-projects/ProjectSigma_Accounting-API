<?php

namespace App\Http\Resources;

use App\Http\Resources\AccountingCollections\StakeholderCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherDetailsCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'stakeholder' => StakeholderCollection::make($this->whenLoaded('stakeholder')),
        ];
    }
}
