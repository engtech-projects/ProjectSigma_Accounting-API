<?php

namespace App\Services;

use App\Enums\PaymentRequestType;
use App\Enums\TransactionFlowStatus;
use App\Models\TransactionFlow;
use App\Models\TransactionFlowModel;
use App\Models\User;
use App\Notifications\RequestTransactionNotification;
use Illuminate\Support\Facades\DB;

class TransactionFlowService
{
    public static function updateTransactionFlow($paymentRequestId, $transactionFlowName, $transactionStatus)
    {
        return DB::transaction(function () use ($paymentRequestId, $transactionFlowName, $transactionStatus) {
            $currentFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('unique_name', $transactionFlowName)
                ->first();
            self::updateForgotTransactionFlow($paymentRequestId, $currentFlow->priority);
            if (! $currentFlow) {
                throw new \Exception('Transaction flow not found');
            }
            if ($currentFlow->priority > 1) {
                $previousFlows = TransactionFlow::where('payment_request_id', $paymentRequestId)
                    ->where('priority', '<', $currentFlow->priority)
                    ->get();
                $pendingFlows = $previousFlows->filter(function ($flow) {
                    return $flow->status === TransactionFlowStatus::PENDING->value || $flow->status === TransactionFlowStatus::IN_PROGRESS->value;
                });
                if ($pendingFlows->isNotEmpty()) {
                    $pendingCount = $pendingFlows->count();
                    $pendingPriorities = $pendingFlows->pluck('priority')->implode(', ');
                    throw new \Exception("Cannot update priority {$currentFlow->priority}. There are {$pendingCount} pending flows (priorities: {$pendingPriorities}) that must be completed first.");
                }
                $nextFlow = TransactionFlow::where('payment_request_id', $paymentRequestId)
                    ->where('priority', $currentFlow->priority + 1)
                    ->first();
                if ($nextFlow) {
                    if ($nextFlow->status === TransactionFlowStatus::PENDING->value) {
                        $nextFlow->update(['status' => TransactionFlowStatus::IN_PROGRESS->value]);
                        if ($nextFlow->user_id) {
                            User::find($nextFlow->user_id)->notify(new RequestTransactionNotification(auth()->user()->token, $nextFlow));
                        }
                    }
                }
            }
            TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('unique_name', $transactionFlowName)
                ->update([
                    'status' => $transactionStatus,
                ]);
        });
    }

    /**
     * @param  string  $paymentRequestType  PaymentRequestType::*->value
     * @param  int|string  $paymentRequestId
     * @return array<int, array{
     *   payment_request_id:int|string,
     *   unique_name:string,
     *   name:string,
     *   user_id:int|string|null,
     *   user_name:string|null,
     *   category:string,
     *   description:string|null,
     *   status:string,
     *   priority:int,
     *   is_assignable:bool,
     *   is_passable:bool
     * }>
     */
    public static function getTransactionFlow($paymentRequestType, $paymentRequestId)
    {
        $excludedCategories = match ($paymentRequestType) {
            PaymentRequestType::PRF->value => [
                PaymentRequestType::PAYROLL->value,
                PaymentRequestType::PO->value,
            ],
            PaymentRequestType::PAYROLL->value => [
                PaymentRequestType::PRF->value,
                PaymentRequestType::PO->value,
            ],
            PaymentRequestType::PO->value => [
                PaymentRequestType::PRF->value,
                PaymentRequestType::PAYROLL->value,
            ],
            default => []
        };
        $templates = TransactionFlowModel::whereNotIn('category', $excludedCategories)
            ->orderBy('priority')
            ->orderBy('id')
            ->get(['unique_name', 'name', 'user_id', 'user_name', 'category', 'description', 'priority', 'is_assignable', 'is_passable']);

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
                    default => TransactionFlowStatus::PENDING->value,
                },
                'priority' => $template->priority,
                'is_assignable' => (bool) $template->is_assignable,
                'is_passable' => (bool) $template->is_passable,
            ];
        })->toArray();
    }

    public static function updateForgotTransactionFlow($paymentRequestId, $priority)
    {
        return DB::transaction(function () use ($paymentRequestId, $priority) {
            TransactionFlow::where('payment_request_id', $paymentRequestId)
                ->where('priority', '<', $priority)
                ->whereIn('status', [
                    TransactionFlowStatus::PENDING->value,
                    TransactionFlowStatus::IN_PROGRESS->value,
                ])
                ->update([
                    'status' => TransactionFlowStatus::DONE->value,
                ]);
        });
    }
}
