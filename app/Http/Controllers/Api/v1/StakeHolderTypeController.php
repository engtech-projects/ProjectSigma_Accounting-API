<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreStakeHolderTypeRequest;
use App\Http\Requests\Api\v1\Update\UpdateStakeHolderTypeRequest;
use App\Http\Resources\collections\StakeHolderTypeCollection;
use App\Http\Resources\resources\StakeHolderTypeResource;
use App\Models\StakeHolderType;
use App\Services\Api\v1\StakeHolderTypeService;
use Illuminate\Http\JsonResponse;

class StakeHolderTypeController extends Controller
{

    protected $stakeHolderTypeService;
    public function __construct(StakeHolderTypeService $stakeHolderTypeService)
    {
        $this->stakeHolderTypeService = $stakeHolderTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stakeHolderType = $this->stakeHolderTypeService->getAll();

        return new StakeHolderTypeCollection($stakeHolderType);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStakeHolderTypeRequest $request)
    {
        $this->stakeHolderTypeService->create($request->validated());

        return new JsonResponse(['message' => "Stakeholder Type successfully created."], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(StakeHolderType $stakeHolderType)
    {
        $stakeHolderType = $this->stakeHolderTypeService->getById($stakeHolderType);

        return new StakeHolderTypeResource($stakeHolderType);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStakeHolderTypeRequest $request, StakeHolderType $stakeHolderType)
    {
        $this->stakeHolderTypeService->update($stakeHolderType, $request->validated());

        return new JsonResponse(['message' => "Stakeholder Type successfully updated."]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StakeHolderType $stakeHolderType)
    {
        $this->stakeHolderTypeService->delete($stakeHolderType);

        return new JsonResponse(['message' => "Stakeholder Type successfully deleted."]);
    }
}
