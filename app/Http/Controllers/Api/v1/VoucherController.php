<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Models\Voucher;
use App\Models\VoucherDetails;
use App\Http\Resources\VoucherResource;
use App\Http\Requests\StoreRequest\VoucherStoreRequest;
use App\Http\Requests\UpdateRequest\VoucherUpdateRequest;
use App\Services\VoucherService;
use App\Models\PaymentRequest;
use App\Models\Form;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\AccountingCollections\VoucherCollection;
use App\Enums\VoucherStatus;

class VoucherController extends Controller
{

	protected $voucherService;

	public function __construct(VoucherService $voucherService)
    {
		$this->voucherService = $voucherService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		$query = Voucher::query();

		if( isset($request->book) )
		{
			$book = Book::byName($request->book)->firstOr(function () {
				return Book::first();
			});

			if( $book )
			{
				$query->filterBook($book->id);
			}
		}

		if( isset($request->status) )
		{
			$query->status($request->status);
		}

		$vouchers = $query->latest('id')
            ->with(['account','stakeholder', 'details'])
            ->paginate(config('services.pagination.limit'));

		return new VoucherCollection($vouchers);
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
    public function store(VoucherStoreRequest $request)
    {
		$voucher = $this->voucherService->create($request->validated());

		if( isset($request->form_type) && isset($request->reference_no) )
		{
			// enhancement - Identify form type
			$prfNumber = $request->reference_no;
			$form = Form::whereHasMorph(
				'formable',
				[PaymentRequest::class],
				function ($query) use ($prfNumber) {
					$query->where('prf_no', $prfNumber);
				}
			)->first();

			$voucher->form_id = $form->id;
			$voucher->save();

		}

		return response()->json($voucher, 201);
    }

    /**voucher
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {

		return response()->json(
			new VoucherResource(
				$voucher->load([
					'stakeholder',
					'account',
					'book',
					'details'
				])
			), 201
		);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherUpdateRequest $request, Voucher $voucher)
    {
        $voucher->update($request->validated());

		// Get current voucher details
		$existingIds = $voucher->details()->pluck('id')->toArray();

		$voucherDetails = $request->details;
		$incomingIds = [];

		foreach ($voucherDetails as $voucherDetail)
		{
			$detail = $voucher->details()->updateOrCreate($voucherDetail);
			$incomingIds[] = $detail->id;
		}
		// Remove voucher details that are no longer present
		$toDelete = array_diff($existingIds, $incomingIds);
		$voucher->details()->whereIn('id', $toDelete)->delete();
		return response()->json(new VoucherResource($voucher), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	// api/voucher/number/{dv or cv}
	public function voucherNo($prefix = 'DV')
	{
		return response()->json(['voucher_no' => Voucher::generateVoucherNo($prefix)], 201);
	}

	public function changeStatus(int $id, VoucherStatus $status)
	{
		$voucher = Voucher::find($id);

		if (!$voucher) {
			return response()->json(['error' => 'voucher not found'], 404);
		}
		// Attempt to update status
		if ($voucher->updateStatus($status)) {
			return response()->json(['message' => 'voucher status updated', 'voucher' => $voucher], 200);
		} else {
			return response()->json(['error' => 'Transition not allowed', 'voucher' => $voucher], 405);
		}
	}

	public function completed(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::Completed);
	}

	public function approved(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::Approved);
	}

	public function rejected(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::Rejected);
	}

	public function void(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::Void);
	}

	public function pending(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::Pending);
	}
}
