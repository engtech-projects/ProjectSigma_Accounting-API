<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\RequestApprovalStatus;
use App\Http\Controllers\Controller;
use App\Notifications\RequestCashVoucherForApprovalNotification;
use App\Notifications\RequestCashVoucherForApprovedNotification;
use App\Notifications\RequestDisbursementVoucherForApprovalNotification;
use App\Notifications\RequestDisbursementVoucherForApprovedNotification;
use App\Notifications\RequestPaymentForApprovalNotification;
use App\Notifications\RequestPaymentForApprovedNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApproveApproval extends Controller
{
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
            $nextApprovalUser = $nextApproval['user_id'];
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
            switch ($modelType) {
                case ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name:
                    $model->notify(new RequestPaymentForApprovedNotification(auth()->user()->token, $model));
                    break;
                case ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name:
                    $model->notify(new RequestDisbursementVoucherForApprovedNotification(auth()->user()->token, $model));
                    break;
                case ApprovalModels::ACCOUNTING_CASH_REQUEST->name:
                    $model->notify(new RequestCashVoucherForApprovedNotification(auth()->user()->token, $model));
                    break;
                default:
                    break;
            }
        }

        return new JsonResponse(['success' => $result['success'], 'message' => $result['message']], $result['status_code']);
    }
}
