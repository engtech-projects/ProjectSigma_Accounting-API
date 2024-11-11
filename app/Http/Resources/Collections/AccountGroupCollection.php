<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\AccountGroupResource;

class AccountGroupCollection extends ResourceCollection
{
	public static $wrap = 'account_group';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
		$this->collection->transform(function ($accountGroup) {
            return new AccountGroupResource($accountGroup);
        });

        return parent::toArray($request);
    }
}
