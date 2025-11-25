<?php

namespace App\Http\Controllers\Actions\Approvals;

use App\Enums\ApprovalModels;
use App\Enums\RequestStatuses;
use App\Http\Controllers\Controller;
use App\Http\Requests\DisapproveApprovalRequest;
use App\Notifications\RequestPaymentForDeniedNotification;
use App\Notifications\RequestVoucherForDeniedNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DisapproveApproval extends Controller
{
    public function __invoke($modelType, $model, DisapproveApprovalRequest $request)
    {
        $cacheKey = "disapprove" . $modelType . $model->id . '-' . Auth::user()->id;
        if (Cache::has($cacheKey)) {
            return new JsonResponse(["success" => false, "message" => "Too Many Attempts"], 429);
        }
        return Cache::remember($cacheKey, 5, function () use ($modelType, $model, $request) {
            return $this->disapprove($modelType, $model, $request);
        });
    }

    public function disapprove($modelType, $model, DisapproveApprovalRequest $request)
    {
        $attribute = $request->validated();
        $result = $model->updateApproval([
            'status' => RequestStatuses::DENIED->value,
            'remarks' => $attribute['remarks'],
            'date_denied' => Carbon::now(),
        ]);

        $notificationMap = [
            ApprovalModels::ACCOUNTING_PAYMENT_REQUEST->name => RequestPaymentForDeniedNotification::class,
            ApprovalModels::ACCOUNTING_DISBURSEMENT_REQUEST->name => RequestVoucherForDeniedNotification::class,
            ApprovalModels::ACCOUNTING_CASH_REQUEST->name => RequestVoucherForDeniedNotification::class,
        ];

        if (isset($notificationMap[$modelType])) {
            $model->notifyCreator($notificationMap[$modelType]);
        }

        return new JsonResponse([
            "success" => $result["success"],
            "message" => $result['message']
        ], $result["status_code"]);
    }
}
