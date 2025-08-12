<?php

namespace App\Services;

use App\Enums\TransactionFlowStatus;
use App\Models\TransactionFlow;

class TransactionFlowService
{
    public function updateTransactionFlow($paymentRequestId, $transactionFlowName)
    {
        $currentFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('name', $transactionFlowName)
            ->first();

        if (!$currentFlow) {
            throw new \Exception('Transaction flow not found');
        }

        if ($currentFlow->priority_number > 1) {
            $previousPriority = $currentFlow->priority_number - 1;

            $previousFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('priority_number', $previousPriority)
                ->first();

            if (!$previousFlow) {
                throw new \Exception("Previous priority flow (priority {$previousPriority}) not found");
            }

            if ($previousFlow->status !== TransactionFlowStatus::DONE->value) {
                throw new \Exception("Cannot update priority {$currentFlow->priority_number}. Previous priority {$previousPriority} must be completed first.");
            }
        }

        TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('name', $transactionFlowName)
            ->update([
                'status' => TransactionFlowStatus::DONE->value,
            ]);
    }

    public function updateTransactionFlowSafe($paymentRequestId, $transactionFlowName)
    {
        $currentFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('name', $transactionFlowName)
            ->first();

        if (!$currentFlow) {
            return false;
        }

        if ($currentFlow->priority_number > 1) {
            $previousPriority = $currentFlow->priority_number - 1;

            $previousFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('priority_number', $previousPriority)
                ->first();

            if (!$previousFlow || $previousFlow->status !== TransactionFlowStatus::DONE->value) {
                return false;
            }
        }

        $updated = TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('name', $transactionFlowName)
            ->update([
                'status' => TransactionFlowStatus::DONE->value,
            ]);

        return $updated > 0;
    }

    public function updateTransactionFlowStrict($paymentRequestId, $transactionFlowName)
    {
        $currentFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('name', $transactionFlowName)
            ->first();

        if (!$currentFlow) {
            throw new \Exception('Transaction flow not found');
        }

        if ($currentFlow->priority_number > 1) {
            $incompletePrevious = TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('priority_number', '<', $currentFlow->priority_number)
                ->where('status', '!=', TransactionFlowStatus::DONE->value)
                ->exists();

            if ($incompletePrevious) {
                throw new \Exception("Cannot update priority {$currentFlow->priority_number}. All previous priorities must be completed first.");
            }
        }

        TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('name', $transactionFlowName)
            ->update([
                'status' => TransactionFlowStatus::DONE->value,
            ]);
    }
}
