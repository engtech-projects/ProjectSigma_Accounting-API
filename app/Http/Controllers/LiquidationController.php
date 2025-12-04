<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Enums\RequestStatuses;
use App\Http\Requests\LiquidationFormRequest;
use App\Models\PaymentRequest;
use App\Models\StakeHolder;
use Illuminate\Http\Request;

class LiquidationController extends Controller
{
    public function index(Request $request)
    {
        $data = PaymentRequest::liquidationRequest()
            ->withStakeholder()
            ->payment()
            ->withTransactionFlow()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        return response()->json([
            'data' => PaymentRequest::find($id)
        ], 200);
    }

    public function store(LiquidationFormRequest $request)
    {
        $validatedData = $request->validated();
        $stakeholder = StakeHolder::where('name', $validatedData['project_code'])->first();
        $validatedData['stakeholder_id'] = $stakeholder?->id ?? null;
        $validatedData['prf_no'] = 'LIQ-' . uniqid();
        $validatedData['type'] = PaymentRequestType::LIQUIDATION->value;
        $validatedData['created_by'] = auth()->user()->id;
        $validatedData['request_status'] = RequestStatuses::PENDING->value;
        $validatedData['description'] = $validatedData['project_code'];
        $validatedData['total_vat_amount'] = 0;
        $validatedData['total'] = $validatedData['amount'];

        $paymentRequest = PaymentRequest::create($validatedData);
        $newDetails = [];
        foreach ($validatedData['details'] as $detail) {
            $newDetails[] = [
                'particulars' => $detail['receipt_no'],
                'amount' => $detail['amount'],
                'stakeholder_id' => $stakeholder->id ?? null
            ];
        }
        $validatedData['details'] = $newDetails;
        $paymentRequest->details()->createMany($validatedData['details']);
        return response()->json([
            'message' => 'Liquidation created successfully',
            'data' => $paymentRequest
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $paymentRequest = PaymentRequest::findOrFail($id);
        $paymentRequest->update($request->all());
        return response()->json([
            'data' => $paymentRequest
        ], 200);
    }

    public function destroy($id)
    {
        $paymentRequest = PaymentRequest::findOrFail($id);
        $paymentRequest->delete();
        return response()->json([
            'message' => 'Payment Request deleted successfully'
        ], 200);
    }
}
