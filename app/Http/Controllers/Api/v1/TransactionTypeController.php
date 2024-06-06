<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\collections\TransactionTypeCollection;
use App\Http\Resources\resources\TransactionTypeResource;
use App\Models\TransactionType;
use App\Http\Requests\Api\v1\Store\StoreTransactionTypeRequest;
use App\Http\Requests\Api\v1\Update\UpdateTransactionTypeRequest;
use App\Services\Api\v1\TransactionTypeService;
use Illuminate\Http\JsonResponse;

class TransactionTypeController extends Controller
{

    protected $transactionTypeService;
    public function __construct(TransactionTypeService $transactionTypeService)
    {
        $this->transactionTypeService = $transactionTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionTypes = $this->transactionTypeService->getTransactionTypeList([
            'book.accounts',
            'stakeholder_group.type_groups.stakeholders'
        ]);

        return new TransactionTypeCollection($transactionTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionTypeRequest $request)
    {
        $data = $request->validated();
        $this->transactionTypeService->createTransactionType($data);

        return new JsonResponse([
            'success' => true,
            'message' => "Transaction type successfully created."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionType $transactionType)
    {
        $transactionType = $this->transactionTypeService->getTransactionTypeById($transactionType, [
            'book.accounts',
            'stakeholder_group.type_groups.stakeholders'
        ]);
        /*         return $transactionType; */
        return new TransactionTypeResource($transactionType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionTypeRequest $request, TransactionType $transactionType)
    {
        $data = $request->validated();
        $this->transactionTypeService->updateTransactionType($transactionType, $data);

        return new JsonResponse([
            'success' => true,
            'message' => "Transaction type successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionType $transactionType)
    {
        $this->transactionTypeService->deleteTransactionType($transactionType);
        return new JsonResponse([
            'success' => true,
            'message' => "Transaction type successfully deleted."
        ]);
    }
}
