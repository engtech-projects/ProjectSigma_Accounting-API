<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportGroup\ReportGroupSearchRequest;
use App\Http\Requests\ReportGroup\ReportGroupStoreRequest;
use App\Http\Resources\ReportGroupCollection;
use App\Models\ReportGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function show(string $id)
    {
        try {
            $rg = ReportGroup::findOrFail($id);
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group fetched successfully',
                'data' => $rg,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group not found',
                'data' => null,
            ], 404);
        }
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
            $reportGroup = DB::transaction(function () use ($id, $validatedData) {
                $rg = ReportGroup::findOrFail($id);
                $rg->update($validatedData);
                return $rg;
            });
            return new JsonResponse([
                'success' => true,
                'message' => 'Report Group successfully updated.',
                'data' => $reportGroup,
            ], 200);
        } catch (\Throwable $e) {
            report($e);
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group failed to update.',
                'data' => null,
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
