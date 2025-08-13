<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionFlowRequest;
use App\Models\Stakeholders\Employee;
use App\Models\TransactionFlowModel;

class TransactionFLowModelController extends Controller
{
    public function index()
    {
        return response()->json(['data' => TransactionFlowModel::all()], 200);
    }

    public function update(TransactionFlowRequest $request, TransactionFlowModel $transactionFlowModel)
    {
        $validatedData = $request->validated();
        $employeeName = Employee::where('source_id', $validatedData['user_id'])->first()->name;
        $validatedData['user_name'] = $employeeName;
        try {
            $transactionFlowModel->update($validatedData);

            return response()->json(['data' => $transactionFlowModel, 'message' => 'Transaction Flow Model Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
