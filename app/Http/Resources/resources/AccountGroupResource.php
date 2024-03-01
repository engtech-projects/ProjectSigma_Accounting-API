<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\collections\BookCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'account_group_id' => $this->account_group_id,
            'account_group_name' => $this->account_group_name,
            'account' => new AccountCollections($this->whenLoaded('account')),
            'book' => new BookCollection($this->whenLoaded('book')),
        ];

        //return parent::toArray($request);
    }
}
