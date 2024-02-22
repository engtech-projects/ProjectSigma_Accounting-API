<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\StakeHolder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Api\v1\StakeholderService;
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
    public function show(StakeHolder $stakeHolder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStakeHolderRequest $request, StakeHolder $stakeHolder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StakeHolder $stakeHolder)
    {
        //
    }
}
