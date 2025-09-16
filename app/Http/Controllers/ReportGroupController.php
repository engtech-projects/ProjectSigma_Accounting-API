<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportGroup\ReportGroupSearchRequest;
use App\Http\Requests\ReportGroup\ReportGroupStoreRequest;
use App\Http\Resources\ReportGroupCollection;
use App\Models\ReportGroup;
use DB;
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
            DB::beginTransaction();
            $reportGroup = ReportGroup::create($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group created successfully',
                'data' => $reportGroup,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group fetched successfully',
            'data' => ReportGroup::find($id),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReportGroupStoreRequest $request, string $id)
    {
        $validatedData = $request->validated();
        try {
            DB::beginTransaction();
            $reportGroup = ReportGroup::findOrFail($id);
            $reportGroup->update($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group Successfully Updated.',
                'data' => $reportGroup,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group Failed to Update.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reportGroup = ReportGroup::find($id);
        if (!$reportGroup) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group Not Found',
                'data' => null,
            ], 404);
        }

        try {
            DB::beginTransaction();
            $reportGroup->delete();
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group deleted successfully',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group Failed to Delete.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
