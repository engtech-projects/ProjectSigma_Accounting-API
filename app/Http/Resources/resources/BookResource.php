<?php

namespace App\Http\Resources\resources;

use App\Http\Resources\collections\AccountCollections;
use App\Http\Resources\collections\AccountGroupCollection;
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
            'account_group' => $this->account_group,
            // "accounts" => AccountResource::collection($this->whenLoaded('accounts')),
            // "account_groups" => $this->whenLoaded('account_groups',function($group) {
            //     return $group->first();
            // }),
            // "account_groups" => new AccountGroupResource('account_group_books'), //AccountGroupResource::collection($this->whenLoaded('account_group_books')),
        ];
        //return parent::toArray($request);
    }
}
