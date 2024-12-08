<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostingPeriodDetails\PostingPeriodDetailsFilter;
use App\Http\Requests\PostingPeriodDetails\PostingPeriodDetailsStore;
use App\Models\Period;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostingPeriodDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostingPeriodDetailsFilter $request)
    {
        $validatedData = $request->validated();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PostingPeriodDetailsStore $request)
    {
        $validatedData = $request->validated();
        try {
            DB::beginTransaction();
            $postingPeriodDetails = Period::create($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Period Details Successfully Created.',
                'data' => $postingPeriodDetails,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Details Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
