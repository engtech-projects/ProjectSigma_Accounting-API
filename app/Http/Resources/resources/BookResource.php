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
            "book_code" => $this->book_code,
            "book_name" => $this->book_name,
            "book_ref" => $this->book_ref,
            "book_flag" => $this->book_flag,
            "book_src" => $this->book_src,
            "book_head" => $this->book_head
        ];
        //return parent::toArray($request);
    }
}
