<?php

namespace App\Http\Resources\AccountingCollections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\JournalEntryResource;

class JournalEntryCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
		return [
            ...parent::toArray($request),
        ];
    }
}
