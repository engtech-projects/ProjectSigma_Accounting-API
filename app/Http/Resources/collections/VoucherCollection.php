<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\VoucherResource;

class VoucherCollection extends ResourceCollection
{
	public static $wrap = 'vouchers';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

		return $this->collection->transform(function ($voucher){
			return new VoucherResource($voucher);
		})->toArray();

    }
}
