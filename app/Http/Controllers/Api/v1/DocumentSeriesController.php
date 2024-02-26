<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\DocumentSeries;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Api\v1\DocumentSeriesService;
use App\Http\Resources\resources\DocumentSeriesResource;
use App\Http\Resources\collections\DocumentSeriesCollection;
use App\Http\Requests\Api\v1\Store\StoreDocumentSeriesRequest;
use App\Http\Requests\Api\v1\Update\UpdateDocumentSeriesRequest;

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
        $documentSeries = $this->documentSeriesService->getAll();

        return new DocumentSeriesCollection($documentSeries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentSeriesRequest $request)
    {
        DocumentSeries::create($request->validated());

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
        $documentSeries = $this->documentSeriesService->getById($documentSeries);

        return new DocumentSeriesResource($documentSeries);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentSeriesRequest $request, DocumentSeries $documentSeries)
    {
        $documentSeries->fill($request->validated())->update();

        return new JsonResponse(['success' => true, 'message' => 'Document series successfully updated.'], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentSeries $documentSeries)
    {
        $documentSeries->delete();

        return new JsonResponse(['success' => true, 'message' => 'Document series successfully deleted.'], JsonResponse::HTTP_OK);
    }
}
