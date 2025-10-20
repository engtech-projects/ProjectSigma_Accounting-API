<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubGroup\SubGroupRequestFilter;
use App\Http\Requests\SubGroup\SubGroupStoreRequest;
use App\Http\Requests\SubGroup\SubGroupSearchRequest;
use App\Models\SubGroup;
use App\Http\Resources\SubGroupCollection;
use App\Http\Resources\SubGroupResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubGroupController extends Controller
{
    /**
     * Display a paginated listing of the resource.
     */
    public function index(SubGroupRequestFilter $subGroupRequestFilter)
    {
        $validatedData = $subGroupRequestFilter->validated();
        $subGroups = SubGroup::filter($validatedData)
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));
        return SubGroupCollection::collection($subGroups)
            ->additional([
                'success' => true,
                'message' => 'Sub Groups fetched successfully'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubGroupStoreRequest $request)
    {
        $validatedData = $request->validated();
        $subGroup = DB::transaction(function () use ($validatedData) {
            return SubGroup::create($validatedData);
        });
        return SubGroupResource::make($subGroup)->additional([
            'success' => true,
            'message' => 'Sub Group Created Successfully',
        ]);
    }

    /**
     * Search for a specific resource.
     */
    public function searchSubGroups(SubGroupSearchRequest $request)
    {
        $validatedData = $request->validated();
        return SubGroupCollection::collection(
            SubGroup::searchByName($validatedData['name'])
                ->limit(10)
                ->get()
        )->additional([
            'success' => true,
            'message' => 'Sub Groups fetched successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubGroupStoreRequest $request, SubGroup $subGroup)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData, $subGroup) {
            $subGroup->update($validatedData);
        });
        return SubGroupResource::make($subGroup->refresh())->additional([
            'success' => true,
            'message' => 'Sub Group Updated Successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubGroup $subGroup)
    {
        $deleted = DB::transaction(function () use ($subGroup) {
            return $subGroup->delete();
        });
        if (!$deleted) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete Sub Group',
                'data' => null,
            ], 500);
        }
        return new JsonResponse([
            'success' => true,
            'message' => 'Sub Group deleted successfully',
            'data' => null,
        ], 200);
    }
}
