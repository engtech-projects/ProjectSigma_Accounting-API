<?php

namespace App\Http\Controllers;

use App\Enums\TransactionFlowStatus;
use App\Enums\TransactionFlowType;
use App\Http\Requests\TransactionFlowRequest;
use App\Models\Stakeholders\Employee;
use App\Models\TransactionFlow;
use App\Models\TransactionFlowModel;

class TransactionFLowModelController extends Controller
{
    public function index()
    {
        return response()->json(['data' => TransactionFlowModel::all()], 200);
    }

    public function update(TransactionFlowRequest $request)
    {
        $validatedData = $request->validated();
        if (!isset($validatedData['flow_id'])) {
            return response()->json(['error' => 'Flow ID is required'], 400);
        }
        $transactionFlowModel = TransactionFlow::find($validatedData['flow_id']);
        if (!$transactionFlowModel) {
            return response()->json(['error' => 'Transaction flow not found'], 404);
        }
        if (isset($validatedData['update_type']) && $validatedData['update_type'] == 'user') {
            if (!isset($validatedData['user_id'])) {
                return response()->json(['error' => 'User ID is required for user update'], 400);
            }
            $employee = Employee::where('source_id', $validatedData['user_id'])->first();
            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }
            $validatedData['user_name'] = $employee->name;
            $validatedData['user_id'] = $employee->source_id;
        }
        if (isset($validatedData['update_type']) && $validatedData['update_type'] == 'status') {
            $authorizedUserId = $validatedData['user_id'] ?? $transactionFlowModel->user_id;
            if (Auth()->user()->employee['id'] === $authorizedUserId) {
                $validatedData['status'] = TransactionFlowStatus::DONE->value;
            } else {
                return response()->json(['error' => 'You are not authorized to update this transaction flow'], 403);
            }
        }
        try {
            $updateData = $validatedData;
            unset($updateData['flow_id']);
            $updateResult = $transactionFlowModel->update($updateData);
            if (!$updateResult) {
                return response()->json(['error' => 'Failed to update transaction flow'], 500);
            }
            $nextFlow = TransactionFlow::where('payment_request_id', $transactionFlowModel->payment_request_id)
                ->where('priority', $transactionFlowModel->priority + 1)
                ->first();
            if ($nextFlow) {
                $nextFlow->update(['status' => TransactionFlowStatus::IN_PROGRESS->value]);
            }
            $transactionFlowModel->refresh();
            return response()->json([
                'data' => $transactionFlowModel,
                'message' => 'Transaction Flow Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }
}
