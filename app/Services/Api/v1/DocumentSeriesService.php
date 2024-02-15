<?php

namespace App\Services\Api\v1;

use App\Models\DocumentSeries;
use Illuminate\Support\Facades\DB;

class DocumentSeriesService
{

    protected $documentSeries;
    public function __construct(DocumentSeries $documentSeries)
    {
        $this->documentSeries = $documentSeries;
    }


    public function getDocumentSeriesList(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
    {
        $query = $this->documentSeries::query();
        if ($relation) {
            $query->with($relation);
        }
        if ($columns) {
            $query->select($columns);
        }
        return $paginate ? $query->paginate() : $query->get();

    }

    public function getDocumentSeriesById($documentSeries)
    {
        return $this->documentSeries->find($documentSeries)->firstOrFail();

    }

    public function createDocumentSeries(array $attribute)
    {
        return DB::transaction(function () use ($attribute) {
            $this->documentSeries->create($attribute);
        });

    }

    public function updateDocumentSeries($documentSeries, array $attribute)
    {
        return DB::transaction(function () use ($documentSeries, $attribute) {
            $documentSeries->update($attribute);
        });
    }


    public function deleteDocumentSeries($documentSeries)
    {
        return DB::transaction(function () use ($documentSeries) {
            $documentSeries->delete();
        });
    }

}
