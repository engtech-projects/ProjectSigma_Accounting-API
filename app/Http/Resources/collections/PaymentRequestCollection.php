<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
// use App\Http\Resources\StakeholderResource;
// use App\Http\Resources\FormResource;
use App\Http\Resources\PaymentRequestResource;

class PaymentRequestCollection extends ResourceCollection
{
	public static $wrap = 'payment_request';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
	
    public function toArray(Request $request): array
    {

		return $this->collection->transform(function ($paymentRequest){
			return new PaymentRequestResource($paymentRequest);
		})->toArray();

    }
}
