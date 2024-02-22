<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\StakeHolder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Api\v1\StakeholderService;
use App\Http\Resources\resources\StakeholderResource;
use App\Http\Resources\collections\StakeholderCollection;
use App\Http\Requests\Api\v1\Store\StoreStakeHolderRequest;
use App\Http\Requests\Api\v1\Update\UpdateStakeHolderRequest;

class StakeHolderController extends Controller
{
    protected $stakeholderService;

    public function __construct(StakeholderService $stakeholderService)
    {
        $this->stakeholderService = $stakeholderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $stakeholders = $this->stakeholderService->getAll();

        return new StakeholderCollection($stakeholders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStakeHolderRequest $request)
    {
        $this->stakeholderService->create($request->validated());

        return new JsonResponse(['message' => "Stakeholder successfully created."], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(StakeHolder $stakeholder)
    {
        $stakeholder = $this->stakeholderService->getById($stakeholder);

        return new StakeholderResource($stakeholder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStakeHolderRequest $request, StakeHolder $stakeholder)
    {
        $this->stakeholderService->update($stakeholder, $request->validated());

        return new JsonResponse(['message' => "Stakeholder successfully updated."], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StakeHolder $stakeHolder)
    {
        $this->stakeholderService->delete($stakeHolder);

        return new JsonResponse(['message' => "Stakeholder successfully deleted."], JsonResponse::HTTP_OK);
    }
}
