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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApproveApproval extends Controller
{
    public function __invoke($modelType, $model, Request $request)
    {
        $cacheKey = "approve" . $modelType . $model->id . '-' . Auth::user()->id;
        if (Cache::has($cacheKey)) {
            return new JsonResponse(["success" => false, "message" => "Too Many Attempts"], 429);
        }
        return Cache::remember($cacheKey, 5, function () use ($modelType, $model, $request) {
            return $this->approve($modelType, $model, $request);
        });
    }

    public function approve($modelType, $model, Request $request)
    {
        $result = $model->updateApproval([
            'status' => RequestApprovalStatus::APPROVED,
            'date_approved' => Carbon::now(),
        ]);
        $nextApproval = $model->getNextPendingApproval();
        if ($nextApproval) {
            $notificationMap = [
                ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name => RequestPaymentForApprovalNotification::class,
                ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name => RequestDisbursementVoucherForApprovalNotification::class,
                ApprovalModels::ACCOUNTING_CASH_REQUEST->name => RequestCashVoucherForApprovalNotification::class,
            ];
            if (isset($notificationMap[$modelType])) {
                $model->notifyNextApprover($notificationMap[$modelType]);
            }
        } else {
            $notificationMap = [
                ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name => RequestPaymentForApprovedNotification::class,
                ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name => RequestDisbursementVoucherForApprovedNotification::class,
                ApprovalModels::ACCOUNTING_CASH_REQUEST->name => RequestCashVoucherForApprovedNotification::class,
            ];
            if (isset($notificationMap[$modelType])) {
                $model->notifyCreator($notificationMap[$modelType]);
            }
        }

        return new JsonResponse([
            "success" => $result["success"],
            "message" => $result['message']
        ], $result["status_code"]);
    }
}
