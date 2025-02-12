<?php

namespace App\Services;

use App\Enums\BalanceType;
use App\Enums\JournalStatus;
use App\Enums\NotationType;
use App\Enums\PaymentRequestType;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Models\PaymentRequest;
use App\Models\Term;
use Carbon\Carbon;

class PaymentServices
{
    public static function getWithPagination(array $validatedData)
    {
        $query = PaymentRequest::query();
        if (isset($validatedData['key'])) {
            $query->where('prf_no', 'LIKE', "%{$validatedData['key']}%");
        }
        if (isset($validatedData['date_from']) && isset($validatedData['date_to'])) {
            $query->whereBetween('request_date', [$validatedData['date_from'], $validatedData['date_to']]);
        }
        $paymentRequest = $query->withStakeholder()
            ->payment()
            ->orderByDesc()
            ->withJournalEntryVouchers()
            ->withPaymentRequestDetails()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function myApprovals()
    {
        $paymentRequest = PaymentRequest::myApprovals()
            ->withStakeholder()
            ->payment()
            ->withJournalEntryVouchers()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function myRequests()
    {
        $paymentRequest = PaymentRequest::myRequests()
            ->withStakeholder()
            ->payment()
            ->orderByDesc()
            ->withPaymentRequestDetails()
            ->withJournalEntryVouchers()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function journalPaymentRequestEntries()
    {
        $paymentRequest = PaymentRequest::isApproved()
            ->withStakeholder()
            ->where(function ($query) {
                $query->whereHas('journalEntry', function ($query) {
                    $query->where('status', JournalStatus::DRAFTED->value);
                })
                    ->orWhereDoesntHave('journalEntry');
            })
            ->withJournalEntryVouchers()
            ->withPaymentRequestDetails()
            ->orderByDesc()
            ->paginate(config('services.pagination.limit'));

        $paymentRequest->map(function ($paymentRequest) {
            if (isset($paymentRequest->type) && $paymentRequest->type === PaymentRequestType::PAYROLL->value) {
                $paymentRequest->details->map(function ($detail) {
                    $terms = Term::where('name', $detail->particulars)->with(['account.accountType'])->first();
                    $detail->account_id = $terms?->account->id;
                    $detail->journalAccountInfo = $terms?->account;
                    $detail->stakeholderInformation = $detail->stakeholder;
                    if ($terms?->account->accountType?->balance_type === BalanceType::DEBIT->value) {
                        $detail->debit = floatval($detail->amount) + floatval($detail->total_amount_vat);
                        $detail->credit = 0;
                    } else if ($terms?->account->accountType?->balance_type === BalanceType::CREDIT->value) {
                        $detail->credit = floatval($detail->amount) + floatval($detail->total_amount_vat);
                        $detail->debit = 0;
                    }
                });
            }
            return $paymentRequest;
        });

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function generatePrfNo($prefix)
    {
        $prefix = strtoupper($prefix);
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
