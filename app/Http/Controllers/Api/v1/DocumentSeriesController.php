<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\DocumentSeriesCollection;
use App\Models\DocumentSeries;
use App\Http\Requests\Api\v1\Store\StoreDocumentSeriesRequest;
use App\Http\Requests\Api\v1\Update\UpdateDocumentSeriesRequest;
use App\Services\Api\v1\DocumentSeriesService;
use Illuminate\Http\JsonResponse;

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
        $this->documentSeriesService->createDocumentSeries($request->validated());

        return new JsonResponse(
            ['success' => true, 'message' => 'Document series successfully created.'],
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentSeries $documentSeries)
    {
        return $this->documentSeriesService->getDocumentSeriesById($documentSeries);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentSeriesRequest $request, DocumentSeries $documentSeries)
    {
        $this->documentSeriesService->updateDocumentSeries($documentSeries, $request->validated());

        return new JsonResponse(['success' => true, 'message' => 'Document series successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentSeries $documentSeries)
    {
        $this->documentSeriesService->deleteDocumentSeries($documentSeries);

        return new JsonResponse(['success' => true, 'message' => 'Document series successfully deleted.']);
    }
}
