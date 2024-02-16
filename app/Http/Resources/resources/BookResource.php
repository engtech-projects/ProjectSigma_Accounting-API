<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\AccountCollections;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*         dd($this); */
        return [
            "book_id" => $this->book_id,
            "book_name" => $this->book_name,
            "symbol" => $this->symbol,
            "account" => new AccountResource($this->whenLoaded('book_accounts', function () {
                return $this->book_accounts->first();
            }))
        ];
        //return parent::toArray($request);
    }
}
