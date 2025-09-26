<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportGroup\ReportGroupRequestFilter;
use App\Http\Requests\ReportGroup\ReportGroupSearchRequest;
use App\Http\Requests\ReportGroup\ReportGroupStoreRequest;
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
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Groups fetched successfully',
            'data' => ReportGroupCollection::collection(
                ReportGroup::filter($validatedData)
                    ->orderByDesc('created_at')
                    ->paginate(config('services.pagination.limit'))
            )->response()->getData(true),
        ], 200);
    }

    /**
     * Search for a specific resource.
     */
    public function searchReportGroups(ReportGroupSearchRequest $request)
    {
        $validatedData = $request->validated();
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Groups fetched successfully',
            'data' => ReportGroupCollection::collection(
                ReportGroup::searchByName($validatedData['name'])
                    ->limit(10)
                    ->get()
            ),
        ], 200);
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
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group created successfully',
            'data' => $reportGroup,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportGroup $reportGroup)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group fetched successfully',
            'data' => $reportGroup,
        ], 200);
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
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group successfully updated.',
            'data' => $reportGroup->fresh(),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportGroup $reportGroup)
    {
        DB::transaction(function () use ($reportGroup) {
            $reportGroup->delete();
        });
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group deleted successfully',
            'data' => null,
        ], 200);
    }
}
