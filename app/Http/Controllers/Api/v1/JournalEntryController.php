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
use App\Http\Resources\Collections\JournalEntryCollection;
use App\Enums\JournalStatus;

class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$query = JournalEntry::query();

		if( isset($request->status) )
		{
			$query->status($request->status);
		}

        $journalEntries = $query->latest('id')->paginate(10);

        return new JournalEntryCollection($journalEntries);
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
    public function show(JournalEntry $journalEntry)
    {
        return response()->json(
			new JournalEntryResource(
				$journalEntry->load(['details'])
			), 201
		);
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
    public function update(JournalUpdateRequest $request, JournalEntry $journalEntry)
    {
        $journalEntry->update($request->validated());

		// Get current voucher details
		$existingIds = $journalEntry->details()->pluck('id')->toArray();

		$journalDetails = $request->details;
		$incomingIds = [];

		foreach ($journalDetails as $journalDetail) 
		{
			$detail = $journalEntry->details()->updateOrCreate($journalDetail);
			$incomingIds[] = $detail->id;
		}
		// Remove voucher details that are no longer present
		$toDelete = array_diff($existingIds, $incomingIds);
		$journalEntry->details()->whereIn('id', $toDelete)->delete();

		return response()->json(new JournalEntryResource($journalEntry->load(['details'])), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function updateStatus(int $id, JournalStatus $status)
	{
		$journal = JournalEntry::find($id);

		if (!$journal) {
			return response()->json(['error' => 'Journal not found'], 404);
		}
		// Attempt to update status
		if ($voucher->updateStatus($status)) {
			return response()->json(['message' => 'Journal status updated', 'voucher' => $journal], 200);
		}
	}

	public function post(int $id)
	{
		return $this->updateStatus($id, JournalStatus::Posted);
	}

	public function open(int $id)
	{
		return $this->updateStatus($id, JournalStatus::Open);
	}

	public function void(int $id)
	{
		return $this->updateStatus($id, JournalStatus::Void);
	}


}
