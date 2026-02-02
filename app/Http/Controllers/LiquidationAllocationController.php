<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiquidationALlocationRequest;
use App\Http\Resources\LiquidationAllocationCollection;
use App\Models\LiquidationAllocation;

class LiquidationAllocationController extends Controller
{
    public function index()
    {
        $allocations = LiquidationAllocation::paginate(Config('services.pagination.limit'));
        return LiquidationAllocationCollection::collection($allocations)->additional([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Retrieved.',
        ]);
    }
    public function store(LiquidationALlocationRequest $request)
    {
        $validatedData = $request->validated();
        $allocation = LiquidationAllocation::create($validatedData);
        return LiquidationAllocationCollection::collection($allocation)->additional([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Created.',
        ]);
    }
    public function update(LiquidationALlocationRequest $request, LiquidationAllocation $allocation)
    {
        $validatedData = $request->validated();
        $allocation->update($validatedData);
        return LiquidationAllocationCollection::collection($allocation)->additional([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Updated.',
        ]);
    }
    public function delete(LiquidationAllocation $allocation)
    {
        $allocation->delete();
        return LiquidationAllocationCollection::collection($allocation)->additional([
            'success' => true,
            'message' => 'Liquidation Allocation Successfully Deleted.',
        ]);
    }
}
