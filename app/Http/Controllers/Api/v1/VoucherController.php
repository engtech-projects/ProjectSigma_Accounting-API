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
// use App\Models\VoucherDetails;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(VoucherResource::collection(Voucher::all()));
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
        $voucher = Voucher::create($request->validated());
		$voucher->details()->createMany($request->details);

		return response()->json(new VoucherResource($voucher), 201);
    }

    /**voucher
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
		return response()->json(new VoucherResource($voucher), 201);	
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
}
