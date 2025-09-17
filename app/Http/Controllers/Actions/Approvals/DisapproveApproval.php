<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\JournalStatus;
use App\Enums\RequestApprovalStatus;
use App\Enums\TransactionFlowName;
use App\Enums\TransactionFlowStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DisapproveApprovalRequest;
use App\Notifications\RequestCashVoucherForDeniedNotification;
use App\Notifications\RequestDisbursementVoucherForDeniedNotification;
use App\Notifications\RequestPaymentForDeniedNotification;
use App\Services\TransactionFlowService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DisapproveApproval extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($modelType, $model, DisapproveApprovalRequest $request)
    {
        $attribute = $request->validated();
        $result = collect($model->updateApproval([
            'status' => RequestApprovalStatus::DENIED,
            'remarks' => $attribute['remarks'],
            'date_denied' => Carbon::now(),
        ]));

        switch ($modelType) {
            case ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name:
                $model->notify(new RequestPaymentForDeniedNotification(auth()->user()->token, $model));
                TransactionFlowService::updateTransactionFlow(
                    $model->journalEntry->paymentRequest->id,
                    TransactionFlowName::PRF_APPROVAL->value,
                    TransactionFlowStatus::REJECTED->value
                );
                break;
            case ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name:
                // journal entry
                $model->journalEntry()->update([
                    'status' => JournalStatus::VOID->value,
                ]);
                // payment request
                $paymentRequest = $model->journalEntry->paymentRequest;
                $paymentRequest->update([
                    'request_status' => RequestApprovalStatus::DENIED,
                ]);
                $model->notify(new RequestDisbursementVoucherForDeniedNotification(auth()->user()->token, $model));
                TransactionFlowService::updateTransactionFlow(
                    $model->id,
                    TransactionFlowName::DISBURSEMENT_VOUCHER_APPROVAL->value,
                    TransactionFlowStatus::REJECTED->value
                );
                break;
            case ApprovalModels::ACCOUNTING_CASH_REQUEST->name:
                // disbursement voucher
                $disbursement = $model->disbursementVoucher;
                $disbursement->update([
                    'request_status' => RequestApprovalStatus::DENIED,
                ]);
                // journal entry
                $journalEntry = $model->journalEntry;
                $journalEntry->update([
                    'status' => JournalStatus::VOID->value,
                ]);
                // payment request
                $paymentRequest = $model->journalEntry->paymentRequest;
                $paymentRequest->update([
                    'request_status' => RequestApprovalStatus::DENIED,
                ]);
                $model->notify(new RequestCashVoucherForDeniedNotification(auth()->user()->token, $model));
                TransactionFlowService::updateTransactionFlow(
                    $model->journalEntry->paymentRequest->id,
                    TransactionFlowName::CASH_VOUCHER_APPROVALS->value,
                    TransactionFlowStatus::REJECTED->value
                );
                break;
            default:
                break;
        }

        return new JsonResponse(['success' => $result['success'], 'message' => $result['message']], JsonResponse::HTTP_OK);
    }
}
