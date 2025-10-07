<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportGroup\ReportGroupRequestFilter;
use App\Http\Requests\ReportGroup\ReportGroupSearchRequest;
use App\Http\Requests\ReportGroup\ReportGroupStoreRequest;
use App\Http\Resources\ReportGroupResource;
use App\Http\Resources\ReportGroupCollection;
use App\Models\ReportGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ReportGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Groups fetched successfully',
            'data' => ReportGroupCollection::collection(ReportGroup::all()),
        ], 200);
    }

    /**
     * Display a paginated listing of the resource.
     */
    public function paginated(ReportGroupRequestFilter $reportGroupRequestFilter)
    {
        $validatedData = $reportGroupRequestFilter->validated();
        $reportGroups = ReportGroup::filter($validatedData)
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));
        return ReportGroupCollection::collection($reportGroups)
            ->additional([
                'success' => true,
                'message' => 'Report Groups fetched successfully'
            ]);
    }

    /**
     * Search for a specific resource.
     */
    public function searchReportGroups(ReportGroupSearchRequest $request)
    {
        $validatedData = $request->validated();
        return ReportGroupCollection::collection(
            ReportGroup::searchByName($validatedData['name'])
                ->limit(10)
                ->get()
        )->additional([
            'success' => true,
            'message' => 'Report Groups fetched successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportGroupStoreRequest $request)
    {
        $validatedData = $request->validated();
        $reportGroup = DB::transaction(function () use ($validatedData) {
            return ReportGroup::create($validatedData);
        });
        return ReportGroupResource::make($reportGroup)->additional([
            'success' => true,
            'message' => 'Report Group Created Successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportGroup $reportGroup)
    {
        return ReportGroupCollection::make($reportGroup)->additional([
            'success' => true,
            'message' => 'Report Group fetched successfully',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReportGroupStoreRequest $request, ReportGroup $reportGroup)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData, $reportGroup) {
            $reportGroup->update($validatedData);
        });
        return ReportGroupResource::make($reportGroup->refresh())->additional([
            'success' => true,
            'message' => 'Report Group Updated Successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportGroup $reportGroup)
    {
        $deleted = DB::transaction(function () use ($reportGroup) {
            return $reportGroup->delete();
        });
        if (!$deleted) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete Report Group',
                'data' => null,
            ], 500);
        }
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group deleted successfully',
            'data' => null,
        ], 200);
    }
}
