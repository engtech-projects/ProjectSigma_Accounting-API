<?php

namespace App\Http\Resources\collections;

use App\Http\Resources\resources\AccountGroupResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountGroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $wrap = 'account_group';
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($accountGroup) {
            return new AccountGroupResource($accountGroup);
        })->toArray();

    }
}
