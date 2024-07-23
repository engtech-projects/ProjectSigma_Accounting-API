<?php

namespace App\Http\Resources\collections;

use App\Http\Resources\resources\AccountTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $wrap = "account_type";

    public function toArray(Request $request): array
    {
        return [
            'data' => AccountTypeResource::collection($this->collection),
        ];
    }
}
