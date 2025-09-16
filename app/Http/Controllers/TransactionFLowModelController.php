<?php

namespace App\Http\Controllers;

use App\Enums\TransactionFlowStatus;
use App\Http\Requests\TransactionFlowRequest;
use App\Models\TransactionFlow;
use App\Models\TransactionFlowModel;
use App\Models\User;

class TransactionFLowModelController extends Controller
{
    public function index()
    {
        return response()->json(['data' => TransactionFlowModel::all()], 200);
    }

    public function update(TransactionFlowRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $transactionFlowModel = null;
            $transactionFlow = null;
            if (! isset($validatedData['flow_id'])) {
                return response()->json(['error' => 'Flow ID is required'], 400);
            }
            if (isset($validatedData['update_type']) && $validatedData['update_type'] == 'user') {
                $transactionFlowModel = TransactionFlowModel::find($validatedData['flow_id']);
                if (! $transactionFlowModel) {
                    return response()->json(['error' => 'Transaction flow not found'], 404);
                }
                if (! isset($validatedData['user_id'])) {
                    return response()->json(['error' => 'User ID is required for user update'], 400);
                }
                $employee = User::where('source_id', $validatedData['user_id'])->first();
                if (! $employee) {
                    return response()->json(['error' => 'Employee not found'], 404);
                }
                $validatedData['user_name'] = $employee->name;
                $validatedData['user_id'] = $employee->source_id;
                $updateResult = $transactionFlowModel->update($validatedData);
                if (! $updateResult) {
                    return response()->json(['error' => 'Failed to update transaction flow'], 500);
                }
            }
            if (isset($validatedData['update_type']) && $validatedData['update_type'] == 'status') {
                $transactionFlow = TransactionFlow::find($validatedData['flow_id']);
                if (! $transactionFlow) {
                    return response()->json(['error' => 'Transaction flow not found'], 404);
                }
                $authorizedUserId = $validatedData['user_id'] ?? $transactionFlow->user_id;
                if (Auth()->user()->id != $authorizedUserId) {
                    return response()->json(['error' => 'You are not authorized to update this transaction flow'], 403);
                }
                $updateResult = $transactionFlow->update($validatedData);
                if (! $updateResult) {
                    return response()->json(['error' => 'Failed to update transaction flow'], 500);
                }
                $nextFlow = TransactionFlow::where('payment_request_id', $transactionFlow->payment_request_id)
                    ->where('priority', $transactionFlow->priority + 1)
                    ->first();
                if ($nextFlow) {
                    $nextFlow->update(['status' => TransactionFlowStatus::IN_PROGRESS->value]);
                }
                $transactionFlow->refresh();
            }

            return response()->json([
                'message' => 'Transaction Flow Updated Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed: '.$e->getMessage()], 500);
        }
    }
}
