<?php

namespace App\Http\Controllers;

use App\Enums\PostingPeriodType;
use App\Http\Controllers\Controller;
use App\Http\Requests\JournalEntryRequest;
use App\Http\Resources\JournalEntryResource;
use App\Models\JournalEntry;
use App\Models\PostingPeriod;
use App\Models\Period;
use App\Http\Requests\StoreRequest\JournalStoreRequest;
use App\Http\Requests\UpdateRequest\JournalUpdateRequest;
use App\Http\Resources\AccountingCollections\JournalEntryCollection;
use App\Enums\JournalStatus;
use App\Services\JournalEntryService;
use DB;
use Symfony\Component\HttpFoundation\JsonResponse;
class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(JournalEntryRequest $request)
    {
        try {
            $validatedData = $request->validated();
            return new JsonResponse([
                'success' => true,
                'message' => 'Journal Entries Successfully Retrieved.',
                'data' => JournalEntryCollection::collection(JournalEntryService::getPaginated($validatedData)),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error retrieving journal entries.',
                'data' => null,
            ], 500);
        }
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
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            $validatedData['posting_period_id'] = PostingPeriod::currentPostingPeriod();
            $validatedData['status'] = JournalStatus::POSTED->value;
            $validatedData['period_id'] = Period::current()->pluck('id')->first();
            if ($validatedData['period_id'] == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'No open period found. Please create a new period. current period',
                    'data' => null,
                ], 400);
            }
            $validatedData['payment_request_id'] = $request->payment_request_id;
            $validatedData['created_by'] = auth()->user()->id;
            $journalEntry = JournalEntry::create($validatedData);
            foreach($request->details as $detail) {
                $journalEntry->details()->create([
                    'account_id' => $detail['journalAccountInfo']['id'] ?? null,
                    'stakeholder_id' => $detail['stakeholder_id'] ?? null,
                    'description' => $detail['description'] ?? null,
                    'debit' => $detail['debit'] ?? null,
                    'credit' => $detail['credit'] ?? null,
                    ''
                ]);
            }
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Journal Entry Successfully Created.',
                'data' => new JournalEntryResource($journalEntry->load('details')),
            ], 201);
        }catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Error creating journal entry.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, JournalEntry $journalEntry)
    {
        $journalEntry = $journalEntry->with(['details'])->paginate(config('service.pagination.limit'));
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entry Successfully Retrieved.',
            'data' => JournalEntryResource::collection($journalEntry)->response()->getData(true)
        ]);
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

	public function changeStatus(int $id, JournalStatus $status)
	{
		$journal = JournalEntry::find($id);

		if (!$journal) {
			return response()->json(['error' => 'journal not found'], 404);
		}

		if ($journal->updateStatus($status)) {
			return response()->json(['message' => 'journal status updated', 'journal' => $journal], 200);
		} else {
			return response()->json(['error' => 'Transition not allowed', 'journal' => $journal], 405);
		}
	}
    public function unpostedEntries()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Unposted Payment Request Entries Successfully Retrieved.',
            'data' => JournalEntryService::unpostedEntries(),
        ], 200);
    }

    public function postedEntries()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Posted Payment Request Entries Successfully Retrieved.',
            'data' => JournalEntryCollection::collection(JournalEntryService::postedEntries())->response()->getData(true),
        ], 200);
    }

    public function draftedEntries()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Drafted Payment Request Entries Successfully Retrieved.',
            'data' => JournalEntryService::draftedEntries(),
        ], 200);
    }
    public function forVoucherEntriesList()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entries for Voucher Successfully Retrieved.',
            'data' => JournalEntryCollection::collection(JournalEntryService::forVoucherEntriesList())->response()->getData(true),
        ], 200);
    }

    public function generateJournalNumber()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Number Successfully Generated.',
            'data' => JournalEntryService::generateJournalNumber(),
        ], 200);
    }
}
