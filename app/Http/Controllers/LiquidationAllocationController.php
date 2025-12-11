<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiquidationALlocationRequest;
use App\Models\LiquidationAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiquidationAllocationController extends Controller
{
    public function index()
    {
        $allocations = LiquidationAllocation::all();
        return new JsonResponse([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Retrieved.',
            'data' => $allocations,
        ], 200);
    }
    public function store(LiquidationALlocationRequest $request)
    {
        $validatedData = $request->validated();
        $allocation = LiquidationAllocation::create($validatedData);
        return new JsonResponse([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Created.',
            'data' => $allocation,
        ], 201);
    }
    public function update(Request $request, LiquidationAllocation $allocation)
    {
        $validatedData = $request->all();
        $allocation->update($validatedData);
        return new JsonResponse([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Updated.',
            'data' => $allocation,
        ], 200);
    }
    public function delete(LiquidationAllocation $allocation)
    {
        $allocation->delete();
        return new JsonResponse([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Deleted.',
        ], 200);
    }
}
