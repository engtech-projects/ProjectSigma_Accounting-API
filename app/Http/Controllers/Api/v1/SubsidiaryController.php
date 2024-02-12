<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreSubsidiaryRequest;
use App\Http\Resources\collections\SubsidiaryCollection;
use App\Http\Resources\resources\SubsidiaryResource;
use App\Models\Subsidiary;
use App\Http\Requests\Api\v1\Update\UpdateSubsidiaryRequest;
use App\Services\Api\v1\SubsidiaryService;
use Illuminate\Http\JsonResponse;

class SubsidiaryController extends Controller
{

    protected $subsidiaryService;
    public function __construct(SubsidiaryService $subsidiaryService)
    {
        $this->subsidiaryService = $subsidiaryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subsidiary = $this->subsidiaryService->getSubsidiaryList();

        return new SubsidiaryCollection($subsidiary);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubsidiaryRequest $request)
    {
        $this->subsidiaryService->createSubsidiary($request->validated());

        return new JsonResponse([
            'success' => true,
            'message' => "Subsidiary successfully created."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subsidiary $subsidiary)
    {
        return new SubsidiaryResource($this->subsidiaryService->getSubsidiaryById($subsidiary));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubsidiaryRequest $request, Subsidiary $subsidiary)
    {
        $this->subsidiaryService->updateSubsidiary($subsidiary, $request->validated());

        return new JsonResponse([
            'success' => true,
            'message' => "Subsidiary successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subsidiary $subsidiary)
    {
        $this->subsidiaryService->deleteSubsidiary($subsidiary);

        return new JsonResponse([
            'success' => true,
            'message' => "Subsidiary successfully deleted."
        ]);
    }
}
