<?php

namespace App\Http\Controllers;

use App\Http\Requests\Withholdingtax\FilterWithHoldingTaxRequest;
use App\Http\Requests\Withholdingtax\StoreWithHoldingTaxRequest;
use App\Http\Requests\Withholdingtax\UpdateWithHoldingTaxRequest;
use App\Http\Resources\AccountingCollections\WithHoldingTaxCollection;
use App\Models\WithHoldingTax;
use App\Services\ApiServices\WithHoldingTaxService;
use Illuminate\Http\JsonResponse;

class WithHoldingTaxController extends Controller
{
    public function index(FilterWithHoldingTaxRequest $request)
    {
        $validatedData = $request->validated();

        return new WithHoldingTaxCollection (WithHoldingTaxService::getPaginated($validatedData));
    }

    public function store(StoreWithHoldingTaxRequest $request)
    {
        $validatedData = $request->validated();
        $withHoldingTax = WithHoldingTax::create($validatedData);

        return new JsonResponse([
            'success' => true,
            'message' => 'Withholding Tax Successfully Created.',
            'data' => new WithHoldingTaxCollection ($withHoldingTax),
        ], 200);
    }

    public function show(WithHoldingTax $withHoldingTax)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Withholding Tax Successfully Created.',
            'data' => new WithHoldingTaxCollection ($withHoldingTax),
        ], 200);
    }

    public function update(UpdateWithHoldingTaxRequest $request, WithHoldingTax $withHoldingTax)
    {
        $validatedData = $request->validated();
        $withholdingTax = WithHoldingTax::find($validatedData['id']);
        if($withholdingTax) {
            $withholdingTax->update($validatedData);
        }
        return new JsonResponse([
            'success' => true,
            'message' => 'Withholding Tax Successfully Updated.',
        ], 200);
    }

    public function destroy($id)
    {
        $withHoldingTax = WithHoldingTax::with('paymentRequest')->find($id);
        if($withHoldingTax->paymentRequest->isNotEmpty()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Withholding Tax cannot be deleted as it is associated with a payment request.',
            ], 400);
        }
        if($withHoldingTax) {
            $withHoldingTax->delete();
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Withholding Tax Successfully Deleted.',
        ], 200);
    }
}
