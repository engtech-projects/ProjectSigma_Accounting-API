<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Resources\JournalEntryResource;
use App\Models\JournalEntry;
use App\Models\PostingPeriod;
use App\Models\Period;
use App\Http\Requests\StoreRequest\JournalStoreRequest;
use App\Http\Requests\UpdateRequest\JournalUpdateRequest;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $journalEntries = JournalEntry::latest('id')->get();
        return response()->json(JournalEntryResource::collection($journalEntries));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JournalStoreRequest $request)
    {
		$postingPeriodId = PostingPeriod::current()->pluck('id')->first();
		$periodId = Period::where('posting_period_id', $postingPeriodId)->current()->pluck('id')->first();
		
		$validated = $request->validated();
		$validated['posting_period_id'] = $postingPeriodId;
		$validated['period_id'] = $periodId;
		
        $journalEntry = JournalEntry::create($validated);

		$journalEntry->details()->createMany($request->details);
		return response()->json(new JournalEntryResource($journalEntry->load('details')), 201);
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
    public function update(JournalUpdateRequest $request, string $id)
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
