<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Enums\RequestStatuses;
use App\Http\Requests\PurchaseOrderRequest;
use App\Http\Requests\PurchaseOrderRequestFilter;
use App\Http\Resources\PaymentRequestCollection;
use App\Models\PaymentRequest;
use App\Models\StakeHolder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(PurchaseOrderRequestFilter $request)
    {
        $validatedData = $request->validated();
        $key = $validatedData['key'] ?? '';
        $purchaseOrders = PaymentRequest::purchaseOrder()
            ->where('prf_no', 'like', '%' . $key . '%')
            ->withDetails()
            ->withTransactionFlow()
            ->purchaseOrder()
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.pagination.limit'));
        return PaymentRequestCollection::collection($purchaseOrders)->additional([
            'success' => true,
            'message' => 'Purchase Orders Successfully Retrieved.',
        ]);
    }
    public function store(PurchaseOrderRequest $request)
    {
        $validatedData = $request->validated();
        return DB::transaction(function () use ($validatedData) {
            $validatedData['type'] = PaymentRequestType::PO->value;
            $validatedData['stakeholder_id'] = StakeHolder::where('name', $validatedData['supplier'])->first()->id;
            $validatedData['request_status'] = RequestStatuses::PENDING->value;
            $validatedData['created_by'] = auth()->user()->id;
            $purchaseOrder = PaymentRequest::create($validatedData);
            $purchaseOrder->details()->createMany($validatedData['details']);
            return (new PaymentRequestCollection($purchaseOrder))->additional([
                'success' => true,
                'message' => 'Purchase Order Successfully Created.',
            ]);
        });
    }
    public function Update(PurchaseOrderRequestFilter $request, PurchaseOrderRequest $purchaseOrderRequest, $id)
    {
        $validatedData = $purchaseOrderRequest->validated();
        $purchaseOrder = PaymentRequest::purchaseOrder()->findOrFail($id);
        $purchaseOrder->update($validatedData);
        $purchaseOrder->details()->delete();
        $purchaseOrder->details()->createMany($validatedData['details']);
        return (new PaymentRequestCollection($purchaseOrder))->additional([
            'success' => true,
            'message' => 'Purchase Order Successfully Updated.',
        ]);
    }
    public function destroy($id)
    {
        $purchaseOrder = PaymentRequest::purchaseOrder()->findOrFail($id);
        $purchaseOrder->delete();
        return response()->json([
            'success' => true,
            'message' => 'Purchase Order Successfully Deleted.',
        ]);
    }
}
