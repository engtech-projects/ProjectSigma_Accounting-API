<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportGroup\ReportGroupSearchRequest;
use App\Http\Requests\ReportGroup\ReportGroupStoreRequest;
use App\Http\Resources\ReportGroupResorce;
use App\Models\ReportGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            'data' => ReportGroupResorce::collection(ReportGroup::all()),
        ], 200);
    }

    public function searchReportGroups(ReportGroupSearchRequest $request)
    {
        $validatedData = $request->validated();

        return new JsonResponse([
            'success' => true,
            'message' => 'Report Groups fetched successfully',
            'data' => ReportGroupResorce::collection(ReportGroup::where('name', 'like', '%'.$validatedData['name'].'%')->limit(10)->get()),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportGroupStoreRequest $request)
    {
        $validatedData = $request->validated();

        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group created successfully',
            'data' => ReportGroup::create($validatedData),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group fetched successfully',
            'data' => ReportGroupResorce::collection(ReportGroup::find($id)),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reportGroup = ReportGroup::find($id)->whereHas('accounts')->first();
        if ($reportGroup) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Report Group Already Exists',
                'data' => null,
            ], 404);
        }

        $reportGroup->delete();

        return new JsonResponse([
            'success' => true,
            'message' => 'Report Group deleted successfully',
            'data' => null,
        ], 200);
    }
}
