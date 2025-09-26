<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportGroup\ReportGroupRequestFilter;
use App\Services\ReportGroupService;
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
    public function paginated(ReportGroupRequestFilter $request)
    {
        $validatedData = $request->validated();
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Groups fetched successfully',
            'data' => ReportGroupCollection::collection(ReportGroupService::getPaginated($validatedData))->response()->getData(true),
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
            'data' => ReportGroupCollection::collection(ReportGroup::where('name', 'like', '%'.$validatedData['name'].'%')->limit(10)->get()),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportGroupStoreRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $reportGroup = DB::transaction(function () use ($validatedData) {
                return ReportGroup::create($validatedData);
            });
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group created successfully',
                'data' => $reportGroup,
            ], 201);
        } catch (\Throwable $e) {
            report($e);
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group failed to create.',
                'data' => null,
            ], 500);
        }
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
        $reportGroup = DB::transaction(function () use ($reportGroup, $validatedData) {
            return ReportGroup::where('id', $reportGroup->id)->update($validatedData);
        });
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group successfully updated.',
            'data' => $reportGroup,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportGroup $reportGroup)
    {
        try {
            DB::transaction(fn () => $reportGroup->delete());
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group deleted successfully',
                'data' => null,
            ], 200);
        } catch (\Throwable $e) {
            report($e);
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group failed to delete.',
                'data' => null,
            ], 500);
        }
    }
}
