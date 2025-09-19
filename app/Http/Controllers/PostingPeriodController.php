<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostingPeriod\PostingPeriodRequestFilter;
use App\Http\Requests\PostingPeriod\PostingPeriodRequestStore;
use App\Http\Resources\PostingPeriodCollection;
use App\Models\PostingPeriod;
use App\Services\PostingPeriodService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class PostingPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostingPeriodRequestFilter $request)
    {
        $validatedData = $request->validated();
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Periods Successfully Retrieved.',
                'data' => PostingPeriodCollection::collection(PostingPeriodService::getPaginated($validatedData))->response()->getData(true),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Groups Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostingPeriodRequestStore $request)
    {
        $validatedData = $request->validated();
        try {
            DB::beginTransaction();
            $postingPeriod = PostingPeriodService::create($validatedData);
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Period Successfully Created.',
                'data' => $postingPeriod,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $postingPeriod = PostingPeriod::find($id)->whereHas('accountGroups')->first();
        if (! $postingPeriod) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Not Found.',
                'data' => null,
            ], 404);
        }
    }
}
