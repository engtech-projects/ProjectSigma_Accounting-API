<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest\PaymentRequestForm;
use App\Http\Requests\UpdateRequest\PaymentUpdateRequestForm;
use App\Models\PaymentRequest;
use App\Models\Form;
use App\Http\Resources\PaymentRequestResource;
use App\Enums\FormStatus;
use App\Enums\FormType;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$paymentRequest = PaymentRequest::all();
		return response()->json(PaymentRequestResource::collection($paymentRequest->load(['stakeholder', 'forms'])));
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

		$form = Form::create([
			'stakeholder_id' => Auth()->user()->id,
			'status' => FormStatus::Pending->value,
		]);

		$paymentRequest->forms()->save($form);
		return response()->json(new PaymentRequestResource($paymentRequest->load(['stakeholder', 'details'])), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentRequest $paymentRequest)
    {
		return response()->json(new PaymentRequestResource($paymentRequest->load(['stakeholder', 'details'])), 200);
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

	
		$existingIds = $paymentRequest->details()->pluck('id')->toArray();

		$paymentRequestDetails = $request->details;
		$incomingIds = [];

		foreach ($paymentRequestDetails as $paymentRequestDetail) 
		{
			$detail = $paymentRequest->details()->updateOrCreate($paymentRequestDetail);
			$incomingIds[] = $detail->id;
		}

		$toDelete = array_diff($existingIds, $incomingIds);
		$paymentRequest->details()->whereIn('id', $toDelete)->delete();
		return response()->json(new PaymentRequestResource($paymentRequest->load(['stakeholder', 'details'])), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	/**
     * 
     */
    public function prfNo($prfNo)
    {
		// return response()->json(['request' => $request->all()]);
        return PaymentRequest::PrfNo($prfNo)->first();
    }

	
}
