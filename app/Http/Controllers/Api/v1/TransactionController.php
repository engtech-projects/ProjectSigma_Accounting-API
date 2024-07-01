<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\TransactionStatus;
use App\Exceptions\DBTransactionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreTransactionRequest;
use App\Http\Requests\Api\v1\Update\UpdateTransactionRequest;
use App\Http\Resources\collections\TransactionCollection;
use App\Http\Resources\resources\TransactionResource;
use App\Models\Pivot\TransactionDetail;
use App\Models\Transaction;
use App\Services\Api\v1\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionService->getAll(
            ['stakeholder', 'transaction_type', 'transaction_details.account', 'transaction_details.stakeholder'],
            ['transaction_type' => $request['transaction_type'], 'status' => TransactionStatus::OPEN->value]
        );
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully Fetched.',
            'data' => TransactionResource::collection($transactions)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $attributes = $request->validated();
/*         try { */
            DB::transaction(function () use ($attributes) {
                $transaction = Transaction::create($attributes);
                $transaction->transaction_details()->createMany($attributes["details"]);
            });
        /* } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 500, $e);
        } */

        return new JsonResponse([
            'success' => true,
            'message' => "Transaction successfully created.",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $data = $this->transactionService->getTransactionById(
            $transaction,
            ['stakeholder', 'transaction_type', 'transaction_details.account', 'transaction_details.stakeholder']
        );
        return new JsonResponse([
            'success' => true,
            'message' => "Successfully fetched.",
            'data' => new TransactionResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Transaction $transaction, UpdateTransactionRequest $request)
    {
        $attributes = $request->validated();
        try {
            DB::transaction(function () use ($attributes, $transaction) {
                $transaction = $transaction->fill($attributes);
                foreach ($attributes["details"] as $attrib) {
                    $detail = TransactionDetail::find($attrib["transaction_detail_id"]);
                    if ($detail) {
                        $detail->update($attrib);
                    } else {
                        $detail->save([
                            "transaction_id" => $attrib["transaction_id"],
                            "stakeholder_group_id" => $attrib["stakeholder_group_id"],
                            "debit" => $attrib["debit"],
                            "credit" => $attrib["credit"]
                        ]);
                    }
                }
                $transaction->save();
            });
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 400, $e);
        }

        return new JsonResponse([
            'success' => true,
            'message' => "Transaction successfully updated.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
        } catch (\Exception $e) {
            throw new DBTransactionException("Delete transaction failed.", 400, $e);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully deleted.',
        ]);
    }
}
