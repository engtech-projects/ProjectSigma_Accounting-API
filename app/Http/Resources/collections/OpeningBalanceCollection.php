<?php

namespace App\Http\Resources\collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OpeningBalanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

     public static $wrap = "opening_balance";
    public function toArray(Request $request): array
    {
        /* return [
            "opening_balance" => $this->collection
        ]; */
        return parent::toArray($request);
    }
}
