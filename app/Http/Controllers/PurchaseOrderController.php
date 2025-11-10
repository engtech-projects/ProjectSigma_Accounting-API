<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Enums\RequestStatuses;
use App\Http\Requests\PurchaseOrderRequest;
use App\Http\Resources\PaymentRequestCollection;
use App\Models\PaymentRequest;
use App\Models\StakeHolder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PaymentRequest::purchaseOrder()
            ->withDetails()
            ->withTransactionFlow()
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
}
