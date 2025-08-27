<?php

namespace App\Services;

use App\Enums\PaymentRequestType;
use App\Enums\TransactionFlowStatus;
use App\Models\TransactionFlow;
use App\Models\TransactionFlowModel;

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

    public static function getTransactionFlow($paymentRequestType, $paymentRequestId)
    {
        $excludedCategories = match ($paymentRequestType) {
            PaymentRequestType::PRF->value => [
                PaymentRequestType::PAYROLL->value,
                PaymentRequestType::PO->value
            ],
            PaymentRequestType::PAYROLL->value => [
                PaymentRequestType::PRF->value,
                PaymentRequestType::PO->value
            ],
            PaymentRequestType::PO->value => [
                PaymentRequestType::PRF->value,
                PaymentRequestType::PAYROLL->value
            ],
            default => []
        };
        $templates = TransactionFlowModel::whereNotIn('category', $excludedCategories)
            ->orderBy('priority')
            ->get(['unique_name', 'name', 'user_id', 'user_name', 'category', 'description', 'priority']);
        return $templates->map(function ($template) use ($paymentRequestId) {
            return [
                'payment_request_id' => $paymentRequestId,
                'unique_name' => $template->unique_name,
                'name' => $template->name,
                'user_id' => $template->user_id,
                'user_name' => $template->user_name,
                'category' => $template->category,
                'description' => $template->description,
                'status' => match ($template->priority) {
                    1 => TransactionFlowStatus::DONE->value,
                    2 => TransactionFlowStatus::IN_PROGRESS->value,
                    default => TransactionFlowStatus::PENDING->value
                },
                'priority' => $template->priority,
            ];
        })->toArray();
    }
}
