<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Http\Resources\VoucherResource;
use App\Http\Requests\StoreRequest\VoucherStoreRequest;
use App\Http\Requests\UpdateRequest\VoucherUpdateRequest;
use App\Services\VoucherService;
use App\Models\PaymentRequest;
use App\Models\Form;
use App\Models\Book;
use App\Http\Resources\AccountingCollections\VoucherCollection;
use App\Enums\VoucherStatus;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VoucherRequest $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Vouchers fetched',
            'data' => VoucherService::getWithPagination($request->validated()),
        ], 201);
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
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $voucher = Voucher::create($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Voucher created',
                'data' => $voucher,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Voucher creation failed',
            ], 500);
        }
		// $voucher = Voucher::create($request->validated());
		// if( isset($request->form_type) && isset($request->reference_no) )
		// {

		// 	$voucher->form_id = $form->id;
		// 	$voucher->save();
		// }
        return new JsonResponse([
            'success' => true,
            'message' => 'Voucher created',
            'data' => $voucher,
        ], 201);
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
	public function voucherNo($prefix = 'DV')
	{
		try {
			return new JsonResponse([
				'success' => true,
				'message' => 'Voucher number generated',
				'data' => Voucher::generateVoucherNo($prefix),
			], 201);
		} catch (\Exception $e) {
			return new JsonResponse([
				'success' => false,
				'message' => "Voucher number generation failed",
			], 500);
		}
	}

	public function changeStatus(int $id, VoucherStatus $status)
	{
		$voucher = Voucher::find($id);
		if (!$voucher) {
			return new JsonResponse([
				'success' => false,
				'message' => 'Voucher not found',
				'data' => null,
			], 404);
		}
		// Attempt to update status
		if ($voucher->updateStatus($status)) {
			return new JsonResponse([
				'success' => true,
				'message' => 'Voucher status updated',
				'data' => $voucher,
			], 200);
		} else {
			return new JsonResponse([
				'success' => false,
				'message' => 'Transition not allowed',
				'data' => $voucher,
			], 405);
		}
	}

	public function completed(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::COMPLETED);
	}

	public function approved(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::APPROVED);
	}

	public function rejected(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::REJECTED);
	}

	public function void(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::VOID);
	}

	public function pending(int $id)
	{
		return $this->changeStatus($id, VoucherStatus::PENDING);
	}
}
