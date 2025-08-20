<?php

namespace App\Services;

use App\Enums\TransactionFlowStatus;
use App\Models\TransactionFlow;

class TransactionFlowService
{
    public static function updateTransactionFlow($paymentRequestId, $transactionFlowName)
    {
        $currentFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('unique_name', $transactionFlowName)
            ->first();

        if (! $currentFlow) {
            throw new \Exception('Transaction flow not found');
        }

        if ($currentFlow->priority > 1) {
            $previousPriority = $currentFlow->priority - 1;

            $previousFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('priority', $previousPriority)
                ->first();

            if (! $previousFlow) {
                throw new \Exception("Previous priority flow (priority {$previousPriority}) not found");
            }

            if ($previousFlow->status !== TransactionFlowStatus::DONE->value) {
                throw new \Exception("Cannot update priority {$currentFlow->priority}. Previous priority {$previousPriority} must be completed first.");
            }
            $nextFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('priority', $currentFlow->priority + 1)
                ->first();
            if ($nextFlow) {
                $nextFlow->update(['status' => TransactionFlowStatus::IN_PROGRESS->value]);
            }
        }

        TransactionFlow::where('payment_request_id', $paymentRequestId)
            ->where('unique_name', $transactionFlowName)
            ->update([
                'status' => TransactionFlowStatus::DONE->value,
            ]);
    }
}
