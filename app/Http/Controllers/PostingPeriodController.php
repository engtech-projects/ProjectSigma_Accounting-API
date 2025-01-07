<?php

namespace App\Http\Controllers;

use App\Enums\PostingPeriodStatusType;
use App\Http\Requests\PostingPeriod\PostingPeriodRequestFilter;
use App\Http\Requests\PostingPeriod\PostingPeriodRequestStore;
use App\Http\Resources\PostingPeriodCollection;
use App\Models\Period;
use App\Models\PostingPeriod;
use App\Services\PostingPeriodService;
use Carbon\Carbon;
use DB;
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

    public function createPostingPeriod()
    {
        $currentDate = Carbon::now();
        $postingPeriod = PostingPeriod::orderBy('period_end', 'desc')->first();
        if (! $postingPeriod || $currentDate > $postingPeriod->period_end) {
            PostingPeriod::query()->update([
                'status' => PostingPeriodStatusType::CLOSED,
            ]);
            Period::query()->update([
                'status' => PostingPeriodStatusType::CLOSED,
            ]);
            $postingPeriod = PostingPeriod::create([
                'period_start' => $currentDate->copy()->startOfYear(),
                'period_end' => $currentDate->copy()->endOfYear(),
            ]);
        }

        $lastPeriod = $postingPeriod->periods()->orderBy('end_date', 'desc')->first();

        if (! $lastPeriod || $currentDate > $lastPeriod->end_date) {
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();

            if (! $postingPeriod->periods()->where('start_date', $startOfMonth)->exists()) {
                $period = $postingPeriod->periods()->create([
                    'start_date' => $startOfMonth,
                    'end_date' => $endOfMonth,
                ]);
            }
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Posting Period Successfully Created',
            'data' => [
                'posting_period' => $postingPeriod,
                'period' => $period ?? null,
            ],
        ], 201);
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
