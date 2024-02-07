<?php

namespace App\Http\Controllers;

use App\Http\Resources\collections\DocumentSeriesCollection;
use App\Models\DocumentSeries;
use App\Http\Requests\Api\v1\Store\StoreDocumentSeriesRequest;
use App\Http\Requests\Api\v1\Update\UpdateDocumentSeriesRequest;
use App\Services\Api\v1\DocumentSeriesService;

class DocumentSeriesController extends Controller
{
    protected $documentSeriesService;
    public function __construct(DocumentSeriesService $documentSeriesService)
    {
        $this->documentSeriesService = $documentSeriesService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentSeries = $this->documentSeriesService->getDocumentSeriesList();

        return new DocumentSeriesCollection($documentSeries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentSeriesRequest $request)
    {
        $documentSeries = $this->documentSeriesService->createDocumentSeries($request->validated());

        return response()->json($documentSeries, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentSeries $documentSeries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentSeriesRequest $request, DocumentSeries $documentSeries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentSeries $documentSeries)
    {
        //
    }
}
