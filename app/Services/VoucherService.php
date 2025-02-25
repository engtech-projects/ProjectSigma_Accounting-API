<?php

namespace App\Services;

use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Http\Resources\CashVoucherResource;
use App\Http\Resources\DisbursementVoucherResource;
use App\Http\Resources\VoucherResource;
use App\Models\Book;
use App\Models\PaymentRequest;
use App\Models\Voucher;
use Carbon\Carbon;
use Str;

class VoucherService
{
    public static function generateVoucherNo($prefix)
    {
        $prefix = Str::upper($prefix);
        $currentYearMonth = Carbon::now()->format('Ym');
        // Find the highest series number based on the prefix:DV/CV
        $lastVoucher = Voucher::where('voucher_no', 'like', "{$prefix}-{$currentYearMonth}-%")
            ->orderBy('voucher_no', 'desc')
            ->first();
        // Extract the last series number if a previous voucher exists
        if ($lastVoucher) {
            $lastSeries = (int) substr($lastVoucher->voucher_no, -4);
            $nextSeries = $lastSeries + 1;
        } else {
            $nextSeries = 1; // Start at 0001 if no previous voucher
        }
        // Format the series number to be 4 digits (e.g., 0001)
        $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
        // Construct the new reference number
        $voucherNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";

        return $voucherNo;
    }

    public static function getWithPagination(array $validatedData)
    {
        $query = Voucher::query();
        if (isset($validatedData['book'])) {
            $book = Book::byName($validatedData['book'])->firstOr(function () {
                return Book::first();
            });
            if ($book) {
                $query->filterBook($book->id);
            }
        }
        if (isset($validatedData['status'])) {
            $query->status($validatedData['status']);
        }
        $voucherRequest = $query->withDetails()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }

    public static function getWithPaginationDisbursement(array $validatedData)
    {
        $disbursementRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->whereDisbursement()
            ->withDetails()
            ->withPaymentRequestDetails()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return DisbursementVoucherResource::collection($disbursementRequest)->response()->getData(true);
    }

    public static function myApprovalsDisbursement(array $validatedData)
    {
        $disbursementRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->whereDisbursement()
            ->withDetails()
            ->withPaymentRequestDetails()
            ->myApprovals()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return DisbursementVoucherResource::collection($disbursementRequest)->response()->getData(true);
    }

    public static function myRequestDisbursement(array $validatedData)
    {
        $disbursementRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            $query->where('voucher_no', 'like', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'like', "%{$validatedData['key']}%");
                });
        })
            ->myRequests()
            ->whereDisbursement()
            ->withDetails()
            ->withPaymentRequestDetails()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return DisbursementVoucherResource::collection($disbursementRequest)->response()->getData(true);
    }

    public static function myVoucheringDisbursement()
    {
        $paymentRequest = PaymentRequest::myApprovals()
            ->withStakeholder()
            ->isApproved()
            ->whereDisbursement()
            ->orderDesc()
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function getWithPaginationCash(array $validatedData)
    {
        $cashRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->withDetails()
            ->whereCash()
            ->orderDesc()
            ->withPaymentRequestDetails()
            ->withStakeholder()
            ->paginate(config('services.pagination.limit'));

        return CashVoucherResource::collection($cashRequest)->response()->getData(true);
    }

    public static function myApprovalsCash(array $validatedData)
    {
        $cashRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->myApprovals()
            ->whereCash()
            ->orderDesc()
            ->withPaymentRequestDetails()
            ->withStakeholder()
            ->paginate(config('services.pagination.limit'));

        return CashVoucherResource::collection($cashRequest)->response()->getData(true);
    }

    public static function myRequestCash(array $validatedData)
    {
        $cashRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->withDetails()
            ->withPaymentRequestDetails()
            ->withStakeholder()
            ->whereCash()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return CashVoucherResource::collection($cashRequest)->response()->getData(true);
    }

    public static function getClearingVouchersCash(array $validatedData)
    {
        $cashRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->isApproved()
            ->whereCash()
            ->withDetails()
            ->withPaymentRequestDetails()
            ->withStakeholder()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return CashVoucherResource::collection($cashRequest)->response()->getData(true);
    }

    public static function getClearedVouchersCash(array $validatedData)
    {
        $cashRequest = Voucher::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('voucher_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->clearedVoucherCash()
            ->whereCash()
            ->withDetails()
            ->withPaymentRequestDetails()
            ->withStakeholder()
            ->withStakeholder()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));

        return CashVoucherResource::collection($cashRequest)->response()->getData(true);
    }
}
