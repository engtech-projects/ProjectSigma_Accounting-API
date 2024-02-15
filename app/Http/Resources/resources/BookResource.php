<?php

namespace App\Http\Resources\resources;

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
        return [
            "book_id" => $this->book_id,
            "book_name" => $this->book_name,
            "symbol" => $this->symbol,
        ];
        //return parent::toArray($request);
    }
}
