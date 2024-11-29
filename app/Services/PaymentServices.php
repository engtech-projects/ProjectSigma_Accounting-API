<?php

namespace App\Services;

use App\Enums\JournalStatus;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Models\PaymentRequest;
use Carbon\Carbon;

class PaymentServices
{
    public static function getWithPagination(array $validatedData)
    {
        $query = PaymentRequest::query();
        if (isset($validatedData['status'])) {
            $query->formStatus($validatedData['status']);
        }
        $paymentRequest = $query->latest('id')
            ->withStakeholder()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function get(array $validatedData)
    {
        $query = PaymentRequest::query();
        if (isset($validatedData['status'])) {
            $query->formStatus($validatedData['status']);
        }
        $paymentRequest = $query->latest('id')
            ->withStakeholder()
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function myApprovals()
    {
        $paymentRequest = PaymentRequest::myApprovals()
            ->withStakeholder()
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function myRequests()
    {
        $paymentRequest = PaymentRequest::myRequests()
            ->withStakeholder()
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function journalPaymentRequestEntries()
    {
        $paymentRequest = PaymentRequest::isApproved()
            ->withStakeholder()
            ->where(function ($query) {
                $query->whereHas('journalEntries', function ($query) {
                    $query->where('status', JournalStatus::DRAFTED->value);
                })
                    ->orWhereDoesntHave('journalEntries');
            })
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function generatePrfNo()
    {
        $prefix = strtoupper('RFA-ACCTG');
        $currentYearMonth = Carbon::now()->format('Y-m');
        // Find the highest series
        $lastPaymentRequest = PaymentRequest::where('prf_no', 'like', "{$prefix}-{$currentYearMonth}-%")
            ->whereNull('deleted_at')
            ->orderBy('prf_no', 'desc')
            ->first();
        // Extract the last series number if a previous request exists
        if ($lastPaymentRequest) {
            $lastSeries = (int) substr($lastPaymentRequest->prf_no, -4); // Get last 4 digits
            $nextSeries = $lastSeries + 1;
        } else {
            $nextSeries = 1; // Start at 0001 if no previous voucher
        }
        // Format the series number to be 4 digits (e.g., 0001)
        $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
        // Construct the new reference number
        $prfNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";

        return $prfNo;
    }
}
