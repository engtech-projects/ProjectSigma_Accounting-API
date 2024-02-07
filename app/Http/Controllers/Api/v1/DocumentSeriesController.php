<?php

namespace App\Http\Controllers;

use App\Models\DocumentSeries;
use App\Http\Requests\StoreDocumentSeriesRequest;
use App\Http\Requests\UpdateDocumentSeriesRequest;

class DocumentSeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentSeriesRequest $request)
    {
        //
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
