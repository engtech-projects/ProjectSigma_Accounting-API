<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\PostingPeriodCollection;
use App\Models\PostingPeriod;
use App\Http\Requests\Api\v1\Store\StorePostingPeriodRequest;
use App\Http\Requests\Api\v1\Update\UpdatePostingPeriodRequest;
use App\Services\Api\V1\PostingPeriodService;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PostingPeriod $postingPeriod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostingPeriodRequest $request, PostingPeriod $postingPeriod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostingPeriod $postingPeriod)
    {
        //
    }
}
