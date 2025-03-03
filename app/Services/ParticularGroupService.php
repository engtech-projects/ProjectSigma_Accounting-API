<?php

namespace App\Services;

use App\Enums\BalanceType;
use App\Enums\JournalStatus;
use App\Enums\PaymentRequestType;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Http\Resources\ParticularGroupCollection;
use App\Models\ParticularGroup;
use App\Models\PaymentRequest;
use App\Models\Term;
use Carbon\Carbon;

class ParticularGroupService
{
    public static function getWithPagination(array $validatedData)
    {
        $particularGroupRequest = ParticularGroup::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('name', 'LIKE', "%{$validatedData['key']}%");
        })
            ->paginate(config('services.pagination.limit'));

        return ParticularGroupCollection::collection($particularGroupRequest)->response()->getData(true);
    }

}
