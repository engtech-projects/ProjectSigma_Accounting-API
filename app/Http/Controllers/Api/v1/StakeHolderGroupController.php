<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreStakeHolderGroupRequest;
use App\Http\Requests\Api\v1\Update\UpdateStakeHolderGroupRequest;
use App\Http\Resources\collections\StakeHolderGroupCollection;
use App\Http\Resources\resources\StakeHolderGroupResource;
use App\Models\StakeHolderGroup;
use App\Services\Api\v1\StakeHolderGroupService;
use Illuminate\Http\JsonResponse;

class StakeHolderGroupController extends Controller
{
    protected $stakeHolderGroupService;

    public function __construct(StakeHolderGroupService $stakeHolderGroupService)
    {
        $this->stakeHolderGroupService = $stakeHolderGroupService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stakeHolderGroup = $this->stakeHolderGroupService->getAll();

        return new StakeHolderGroupCollection($stakeHolderGroup);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStakeHolderGroupRequest $request)
    {
        $this->stakeHolderGroupService->create($request->validated());

        return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholder Group successfully created.'
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(StakeHolderGroup $stakeHolderGroup)
    {
        $stakeHolderGroup = $this->stakeHolderGroupService->getById($stakeHolderGroup);

        return new StakeHolderGroupResource($stakeHolderGroup);

        /* return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholder Group successfully updated.'
        ], JsonResponse::HTTP_OK); */
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStakeHolderGroupRequest $request, StakeHolderGroup $stakeHolderGroup)
    {
        $this->stakeHolderGroupService->update($stakeHolderGroup, $request->validated());

        return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholder Group successfully updated.'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StakeHolderGroup $stakeHolderGroup)
    {
        $stakeHolderGroup->delete();

        return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholder Group successfully deleted.'
        ], JsonResponse::HTTP_OK);
    }
}
