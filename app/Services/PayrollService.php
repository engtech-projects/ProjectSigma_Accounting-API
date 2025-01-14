<?php

namespace App\Services;

use App\Http\Resources\AccountingCollections\PaymentRequestResource;
use App\Models\PaymentRequest;

class PayrollService
{
    public static function withPagination($filter)
    {
        $query = PaymentRequest::query();
        if (isset($filter['status'])) {
            $query->formStatus($filter['status']);
        }
        $payrollRequest = $query->withStakeholder()
            ->payroll()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestResource::collection($payrollRequest)->response()->getData(true);
    }

    public static function getAll($filter)
    {
        $query = PaymentRequest::query();
        if (isset($filter['status'])) {
            $query->formStatus($filter['status']);
        }
        $payrollRequest = $query->latest('id')
            ->withStakeholder()
            ->payroll()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->with('created_by_user')
            ->get();

        return PaymentRequestResource::collection($payrollRequest)->response()->getData(true);
    }

    public static function myRequests()
    {
        $query = PaymentRequest::query();
        $payrollRequest = $query->myRequests()
            ->withStakeholder()
            ->payroll()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestResource::collection($payrollRequest)->response()->getData(true);
    }

    public static function myApprovals()
    {
        $query = PaymentRequest::query();
        $payrollRequest = $query->myApprovals()
            ->withStakeholder()
            ->payroll()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestResource::collection($payrollRequest)->response()->getData(true);
    }
}
