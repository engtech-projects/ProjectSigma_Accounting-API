<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\JournalStatus;
use App\Enums\RequestApprovalStatus;
use App\Enums\TransactionFlowName;
use App\Enums\TransactionFlowStatus;
use App\Http\Controllers\Controller;
use App\Notifications\RequestCashVoucherForApprovalNotification;
use App\Notifications\RequestCashVoucherForApprovedNotification;
use App\Notifications\RequestDisbursementVoucherForApprovalNotification;
use App\Notifications\RequestDisbursementVoucherForApprovedNotification;
use App\Notifications\RequestPaymentForApprovalNotification;
use App\Notifications\RequestPaymentForApprovedNotification;
use App\Services\TransactionFlowService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApproveApproval extends Controller
{
    protected $transactionFlowService;

    public function __construct(TransactionFlowService $transactionFlowService)
    {
        $this->transactionFlowService = $transactionFlowService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke($modelType, $model, Request $request)
    {
        $result = $model->updateApproval([
            'status' => RequestApprovalStatus::APPROVED,
            'date_approved' => Carbon::now(),
        ]);

        $nextApproval = $model->getNextPendingApproval();
        if ($nextApproval) {
            switch ($modelType) {
                case ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name:
                    $model->notify(new RequestPaymentForApprovalNotification(auth()->user()->token, $model));
                    break;
                case ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name:
                    $model->notify(new RequestDisbursementVoucherForApprovalNotification(auth()->user()->token, $model));
                    break;
                case ApprovalModels::ACCOUNTING_CASH_REQUEST->name:
                    $model->notify(new RequestCashVoucherForApprovalNotification(auth()->user()->token, $model));
                    break;
                default:
                    break;
            }
        } else {
            try {
                switch ($modelType) {
                    case ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name:
                        $this->transactionFlowService->updateTransactionFlow(
                            $model->id,
                            TransactionFlowName::PRF_APPROVAL->value,
                            TransactionFlowStatus::DONE->value
                        );
                        $model->notify(new RequestPaymentForApprovedNotification(auth()->user()->token, $model));
                        break;
                    case ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name:
                        $this->transactionFlowService->updateTransactionFlow(
                            $model->journalEntry->paymentRequest->id,
                            TransactionFlowName::DISBURSEMENT_VOUCHER_APPROVAL->value,
                            TransactionFlowStatus::DONE->value
                        );
                        $model->journalEntry()->update([
                            'status' => JournalStatus::UNPOSTED->value,
                        ]);
                        $model->notify(new RequestDisbursementVoucherForApprovedNotification(auth()->user()->token, $model));
                        break;
                    case ApprovalModels::ACCOUNTING_CASH_REQUEST->name:
                        $this->transactionFlowService->updateTransactionFlow(
                            $model->journalEntry->paymentRequest->id,
                            TransactionFlowName::CASH_VOUCHER_APPROVALS->value,
                            TransactionFlowStatus::DONE->value
                        );
                        $model->journalEntry()->update([
                            'status' => JournalStatus::UNPOSTED->value,
                        ]);
                        $model->notify(new RequestCashVoucherForApprovedNotification(auth()->user()->token, $model));
                        break;
                    default:
                        break;
                }
            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Transaction flow update failed: '.$e->getMessage(),
                ], 422);
            }
        }

        return new JsonResponse(['success' => $result['success'], 'message' => $result['message']], $result['status_code']);
    }
}
