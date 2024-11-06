<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\JournalEntryResource;

class JournalEntryCollection extends ResourceCollection
{	
	public static $wrap = 'journal';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
		return $this->collection->transform(function ($journal){
			return new JournalEntryResource($journal);
		})->toArray();

    }
}
