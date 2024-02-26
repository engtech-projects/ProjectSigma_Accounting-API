<?php

namespace App\Services\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Models\DocumentSeries;
use Exception;
use Illuminate\Support\Facades\DB;

class DocumentSeriesService
{

    protected $documentSeries;
    public function __construct(DocumentSeries $documentSeries)
    {
        $this->documentSeries = $documentSeries;
    }


    public function getAll(?array $relation = [], ?bool $paginate = false, ?array $columns = [])
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

    public function getById($documentSeries)
    {
        return $this->documentSeries->find($documentSeries)->firstOrFail();

    }

    public function createDocumentSeries(array $attribute)
    {
        try {
            $this->documentSeries->create($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        }

    }

    public function updateDocumentSeries($documentSeries, array $attribute)
    {
        try {
            $documentSeries->update($attribute);
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 500, $e);
        }
    }


    public function deleteDocumentSeries($documentSeries)
    {
        try {
            $documentSeries->delete();
        } catch (Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 500, $e);
        }

    }

}
