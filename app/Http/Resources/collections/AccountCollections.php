<?php

namespace App\Http\Resources\collections;

use App\Http\Resources\resources\AccountTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountCollections extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $request = "accounts";
    public function toArray(Request $request): array
    {
        return [
            "data" => $this->collection
        ];
        //return parent::toArray($request);


    }


}
