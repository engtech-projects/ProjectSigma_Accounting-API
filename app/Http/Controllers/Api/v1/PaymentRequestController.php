<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentFilterRequest;
use App\Http\Requests\SearchStakeHolderRequest;
use App\Http\Requests\StoreRequest\PaymentRequestFormRequest;
use App\Http\Requests\UpdateRequest\PaymentUpdateRequestForm;
use App\Models\PaymentRequest;
use App\Models\Form;
use App\Http\Resources\PaymentRequestResource;
use App\Enums\FormStatus;
use App\Services\PaymentServices;
use App\Services\StakeHolderService;
use Illuminate\Http\JsonResponse;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaymentFilterRequest $request)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Payment Requests Successfully Retrieved.',
                'data' => PaymentServices::getWithPagination($request->validated()),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Payment Requests Failed to Retrieve.',
            ], 500);
        }
    }
    public function searchStakeHolders(SearchStakeHolderRequest $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholders Successfully Retrieved.',
            'data' => StakeHolderService::searchStakeHolders($request->validated()),
        ], 200);
    }
    public function get(PaymentFilterRequest $request)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Payment Requests Successfully Retrieved.',
                'data' => PaymentServices::get($request->validated()),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Payment Requests Failed to Retrieve.',
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
    public function store(PaymentRequestFormRequest $request)
    {
        $validatedData = $request->validated();
		$prfNo = PaymentServices::generatePrfNo();
		$validatedData['prf_no'] = $prfNo;
		$paymentRequest = PaymentRequest::create($validatedData);
		foreach($validatedData['details'] as $detail) {
			$paymentRequest->details()->create([
				'particulars' => $detail['particulars'] ?? null,
				'cost' => $detail['cost'] ?? null,
				'vat' => $detail['vat'] ?? null,
				'amount' => $detail['amount'] ?? null,
				'chargeable_id' => $detail['id'],
				'chargeable_type' => $detail['type']
			]);
		}
        return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request Created Successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentRequest $paymentRequest)
    {
		return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request Successfully Retrieved.',
            'data' => new PaymentRequestResource($paymentRequest->load(['stakeholder', 'details.chargeable'])),
        ], 200);
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
		return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request Successfully Updated.',
            'data' => new PaymentRequestResource($paymentRequest->load(['stakeholder', 'details'])),
        ], 200);
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
		return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request Successfully Retrieved.',
            'data' => PaymentRequest::PrfNo($prfNo)->withStakeholder()->first(),
        ], 200);
    }
}
