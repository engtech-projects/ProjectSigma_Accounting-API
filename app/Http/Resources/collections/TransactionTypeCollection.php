<?php

namespace App\Http\Resources\collections;

use App\Http\Resources\resources\BookResource;
use App\Http\Resources\resources\StakeHolderGroupResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($value) {
            return [
                'transaction_type' => $value->transaction_type_id,
                'transaction_type_name' => $value->transaction_type_name,
                'book' => new BookResource($value->book),
                'stakeholder_group' => new StakeHolderGroupResource($value->stakeholder_group),
            ];
        })->toArray();
    }
}
