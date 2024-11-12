<?php

namespace App\Http\Resources\AccountingCollections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
// use App\Http\Resources\StakeholderResource;
// use App\Http\Resources\FormResource;
use App\Http\Resources\PaymentRequestResource;

class PaymentRequestCollection extends ResourceCollection
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
        ];
    }
}
