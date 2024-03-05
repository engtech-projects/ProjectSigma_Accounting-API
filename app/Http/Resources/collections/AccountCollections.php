<?php

namespace App\Http\Resources\collections;

use App\Http\Resources\resources\AccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountCollections extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $wrap = 'account';

    public function toArray(Request $request): array
    {
        $this->collection->transform(function ($account) {
            return new AccountResource($account);
        });

        /*         return [
                    "account" => $this->collection
                ]; */
        return parent::toArray($request);
    }


}
