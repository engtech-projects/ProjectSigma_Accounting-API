<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostingPeriodRequest;
use App\Http\Requests\PostingPeriodRequest;
use App\Http\Resources\PostingPeriodCollection;
use App\Services\PostingPeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\PostingPeriodResource;
use App\Models\PostingPeriod;
class PostingPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostingPeriodRequest $request)
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
     * Show the form for creating a new resource.
     */
    public function create(CreatePostingPeriodRequest $request)
    {
        $validatedData = $request->validated();
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Period Successfully Created.',
                'data' => new PostingPeriodResource(PostingPeriodService::create($validatedData)),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostingPeriodRequest $request)
    {
        $validatedData = $request->validated();
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Posting Period Successfully Created.',
                'data' => new PostingPeriodResource(PostingPeriodService::create($validatedData)),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Failed to Create.',
                'data' => null,
            ], 500);
        }
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
        $postingPeriod = PostingPeriod::find($id)->whereHas('accountGroups')->first();
        if (!$postingPeriod) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Posting Period Not Found.',
                'data' => null,
            ], 404);
        }
    }

	public function updatePeriodStatus(PostingPeriod $postingPeriod)
	{

	}
}
