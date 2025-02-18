<?php

namespace App\Http\Controllers;

use App\Http\Requests\Withholdingtax\FilterWithHoldingTaxRequest;
use App\Http\Requests\Withholdingtax\StoreWithHoldingTaxRequest;
use App\Http\Requests\Withholdingtax\UpdateWithHoldingTaxRequest;
use App\Http\Resources\AccountingCollections\WithHoldingTaxCollection;
use App\Models\WithHoldingTax;
use App\Services\WithHoldingTaxService;
use Illuminate\Http\JsonResponse;

class WithHoldingTaxController extends Controller
{
    public function index(FilterWithHoldingTaxRequest $request)
    {
        $validatedData = $request->validated();

        return WithHoldingTaxCollection::collection(WithHoldingTaxService::getPaginated($validatedData));
    }

    public function store(StoreWithHoldingTaxRequest $request)
    {
        $validatedData = $request->validated();
        $withHoldingTax = WithHoldingTax::create($validatedData);

        return new JsonResponse([
            'success' => true,
            'message' => 'Withholding Tax Successfully Created.',
            'data' => WithHoldingTaxCollection::collection($withHoldingTax),
        ], 200);
    }

    public function show(WithHoldingTax $withHoldingTax)
    {
        return new WithHoldingTaxCollection($withHoldingTax);
    }

    public function update(UpdateWithHoldingTaxRequest $request, WithHoldingTax $withHoldingTax)
    {
        $withHoldingTax->update($request->all());

        return new WithHoldingTaxCollection($withHoldingTax);
    }

    public function destroy(WithHoldingTax $withHoldingTax)
    {
        $withHoldingTax->delete();

        return response()->json(null, 204);
    }
}
