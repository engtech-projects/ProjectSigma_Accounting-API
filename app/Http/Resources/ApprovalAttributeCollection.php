<?php

namespace App\Http\Resources;

use App\Services\ApiServices\HrmsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalAttributeCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return HrmsService::formatApprovals($request->bearerToken(), $this->resource);
    }
}
