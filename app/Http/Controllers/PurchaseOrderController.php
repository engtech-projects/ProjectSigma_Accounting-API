<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Http\Requests\PurchaseOrderRequest;
use App\Http\Resources\PaymentRequestCollection;
use App\Models\PaymentRequest;
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
        $validatedData =  $request->validated();
        DB::transaction(function () use ($validatedData) {
            $validatedData['type'] = PaymentRequestType::PO->value;
            $purchaseOrder = PaymentRequest::create($validatedData);
            $purchaseOrder->details()->createMany($validatedData['details']);
            return PaymentRequestCollection::collection($purchaseOrder)->additional([
                'success' => true,
                'message' => 'Purchase Orders Successfully Retrieved.',
            ]);
        });
    }
}
