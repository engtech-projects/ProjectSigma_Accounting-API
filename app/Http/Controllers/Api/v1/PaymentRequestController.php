<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowStatusRequest;
use App\Http\Requests\StoreRequest\PaymentRequestFormRequest;
use App\Http\Requests\UpdateRequest\PaymentUpdateRequestForm;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Models\PaymentRequest;
use App\Models\Form;
use App\Http\Resources\PaymentRequestResource;
use App\Enums\FormStatus;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShowStatusRequest $request)
    {
        $validatedData = $request->validated();
		$query = PaymentRequest::query();
		if( isset($validatedData['status']) )
		{
			$query->formStatus($validatedData['status']);
		}
		$paymentRequest = $query->latest('id')->with(['stakeholder'])->paginate(15);
		return new PaymentRequestCollection($paymentRequest);
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
    public function store(PaymentRequestFormRequest $request)
    {
        $validatedData = $request->validated();
		$prfNo = PaymentRequest::generatePrfNo();
		$validatedData['prf_no'] = $prfNo;
		$paymentRequest = PaymentRequest::create($validatedData);
		$paymentRequest->details()->createMany($validatedData['details']);
		$form = Form::create([
			'stakeholder_id' => Auth()->user()->id,
			'status' => FormStatus::Pending->value,
		]);
		$paymentRequest->forms()->save($form);
        return response()->json(['message' => 'Payment Request Created Successfully'], 201);
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
