<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\DBTransactionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Store\StoreTransactionRequest;
use App\Http\Requests\Api\v1\Update\UpdateTransactionRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();
        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully Fetched.',
            'data' => $transactions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $attributes = $request->validated();
        /* try { */
            $transaction = Transaction::create($attributes);
            $transaction->transaction_details()->createMany($attributes["details"]);
        /* } catch (Exception $e) {
            throw new DBTransactionException("Create transaction failed.", 404, $e);
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
        $data = $transaction->with('transaction_details')->first();
        return new JsonResponse([
            'success' => true,
            'message' => "Successfully fetched.",
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Transaction $transaction, UpdateTransactionRequest $request)
    {
        $attributes = $request->validated();
        try {
            $transaction->update($attributes);
        } catch (Exception $e) {
            throw new DBTransactionException("Update transaction failed.", 404, $e);
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
