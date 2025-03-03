<?php

namespace App\Services;

use App\Http\Resources\TermsCollection;
use App\Http\Resources\TermsCollections;
use App\Models\Term;

class TermsService
{
    public static function getPaginated(array $validateData)
    {
        $queryTermsRequest = Term::when(isset($validateData['key']), function ($query, $key) use ($validateData) {
            $query->where('name', 'like', '%' . $validateData['key'] . '%');
        })
            ->paginate(config('services.pagination.limit'));

        return (TermsCollection::collection($queryTermsRequest)->response()->getData(true));
    }
}
