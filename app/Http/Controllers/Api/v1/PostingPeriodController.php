<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\PostingPeriodCollection;
use App\Http\Resources\resources\PostingPeriodResource;
use App\Models\PostingPeriod;
use App\Http\Requests\Api\v1\Store\StorePostingPeriodRequest;
use App\Http\Requests\Api\v1\Update\UpdatePostingPeriodRequest;
use App\Services\Api\v1\PostingPeriodService;
use Illuminate\Http\JsonResponse;

class PostingPeriodController extends Controller
{

    protected $postingPeriodService;

    public function __construct(PostingPeriodService $postingPeriodService)
    {
        $this->postingPeriodService = $postingPeriodService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postingPeriods = $this->postingPeriodService->getPostingPeriodList();
        return new PostingPeriodCollection($postingPeriods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostingPeriodRequest $request)
    {
        $data = $request->validated();
        $this->postingPeriodService->createPostingPeriod($data);
        return new JsonResponse([
            'success' => true,
            'message' => 'Posting period successfully created.'
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(PostingPeriod $postingPeriod)
    {
        $postingPeriod = $this->postingPeriodService->getPostingPeriod($postingPeriod);
        return new PostingPeriodResource($postingPeriod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostingPeriodRequest $request, PostingPeriod $postingPeriod)
    {
        $data = $request->validated();
        $this->postingPeriodService->updatePostingPeriod($postingPeriod, $data);

        return new JsonResponse([
            'success' => true,
            'message' => 'Posting period successfully updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostingPeriod $postingPeriod)
    {
        $this->postingPeriodService->deletePostingPeriod($postingPeriod);
        return new JsonResponse([
            'success' => true,
            'message' => 'Posting period successfully deleted.'
        ]);
    }
}
