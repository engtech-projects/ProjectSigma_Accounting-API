<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest\PaymentRequestForm;
use App\Http\Requests\UpdateRequest\PaymentUpdateRequestForm;
use App\Models\PaymentRequest;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'test';
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
    public function store(PaymentRequestForm $request)
    {
		$prfNo = PaymentRequest::generatePrfNo();
		$validated = $request->validated();
		$validated['prf_no'] = $prfNo;
		$paymentRequest = PaymentRequest::create($validated);

		$details = $request->details;

		$paymentRequest->details()->createMany($request->details);

		// create form
		// create 
		// return $validated;
		return response()->json(['Payment Request' => $paymentRequest], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentRequest $paymentRequest)
    {
        return response()->json($paymentRequest->load('details'), 200); 
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
    public function update(PaymentUpdateRequestForm $request, PaymentRequest $paymentRequest)
    {
		$paymentRequest->update($request->validated());

		// Get current voucher details
		$existingIds = $paymentRequest->details()->pluck('id')->toArray();

		$paymentRequestDetails = $request->details;
		$incomingIds = [];

		foreach ($paymentRequestDetails as $paymentRequestDetail) 
		{
			$detail = $paymentRequest->details()->updateOrCreate($paymentRequestDetail);
			$incomingIds[] = $detail->id;
		}
		// Remove voucher details that are no longer present
		$toDelete = array_diff($existingIds, $incomingIds);
		$paymentRequest->details()->whereIn('id', $toDelete)->delete();
		return response()->json(['Payment Request' => $paymentRequest->load('details')], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
