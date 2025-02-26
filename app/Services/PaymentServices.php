<?php

namespace App\Services;

use App\Enums\BalanceType;
use App\Enums\JournalStatus;
use App\Enums\PaymentRequestType;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Models\PaymentRequest;
use App\Models\Term;
use App\Services\ApiServices\WithHoldingTaxService;
use Carbon\Carbon;

class PaymentServices
{
    public static function getWithPagination(array $validatedData)
    {
        $paymentRequest = PaymentRequest::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('prf_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->withStakeholder()
            ->payment()
            ->orderByDesc('created_at')
            ->withJournalEntryVouchers()
            ->withPaymentRequestDetails()
            ->withHoldingTax()
            ->with('created_by_user')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function myApprovals(array $validatedData)
    {
        $paymentRequest = PaymentRequest::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('prf_no', 'LIKE', "%{$validatedData['key']}")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}");
                });
        })
            ->withStakeholder()
            ->payment()
            ->withJournalEntryVouchers()
            ->withPaymentRequestDetails()
            ->myApprovals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }

    public static function myRequests(array $validatedData)
    {
        $paymentRequest = PaymentRequest::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('prf_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })

            ->withStakeholder()
            ->payment()
            ->orderByDesc('created_at')
            ->withPaymentRequestDetails()
            ->withJournalEntryVouchers()
            ->myRequests()
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
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        $paymentRequest->map(function ($paymentRequest) {
            $paymentRequest->details->map(function ($detail) {
                $detail->debit = floatval($detail->amount);
                $detail->credit = 0;
            });
            if (floatval($paymentRequest->total_vat_amount) > 0) {
                $accountVat = (object) AccountService::getVatAccount();
                $paymentRequest->details[] = [
                    'id' => $accountVat->id,
                    'journalAccountInfo' => $accountVat->information,
                    'amount' => floatval($paymentRequest->total_vat_amount),
                    'cost' => floatval($paymentRequest->total_vat_amount),
                    'particulars' => 'INPUT VAT',
                    'debit' => 0,
                    'credit' => floatval($paymentRequest->total_vat_amount),
                ];
            }
            if ($paymentRequest->with_holding_tax_id) {
                $withholdingTax = (object) WithHoldingTaxService::getWithHoldingTax($paymentRequest->with_holding_tax_id);
                $accountWithHoldingTax = (object) AccountService::getWithHoldingTaxAccount($withholdingTax->information->account_id);
                $withHoldingTaxAmount = floatval($paymentRequest->taxableAmount) * floatval($withholdingTax->information->wtax_percentage / 100);
                $paymentRequest->details[] = [
                    'id' => $accountWithHoldingTax->id,
                    'journalAccountInfo' => $accountWithHoldingTax->information,
                    'particulars' => 'WITHHOLDING TAX ('.$withholdingTax?->information?->wtax_percentage_formatter.')',
                    'amount' => floatval($withHoldingTaxAmount),
                    'cost' => floatval($withHoldingTaxAmount),
                    'debit' => 0,
                    'credit' => floatval($withHoldingTaxAmount),
                ];
            }
            if (isset($paymentRequest->type) && $paymentRequest->type === PaymentRequestType::PAYROLL->value) {
                $paymentRequest->details->map(function ($detail) {
                    $terms = Term::where('name', $detail->particulars)->with(['account.accountType'])->first();
                    $detail->account_id = $terms?->account->id;
                    $detail->journalAccountInfo = $terms?->account;
                    $detail->stakeholderInformation = $detail->stakeholder;
                    if (! $terms?->debit_credit) {
                        $detail->debit = floatval($detail->amount);
                        $detail->credit = 0;
                    } elseif ($terms?->debit_credit === BalanceType::DEBIT->value) {
                        $detail->debit = floatval($detail->amount);
                        $detail->credit = 0;
                    } elseif ($terms?->debit_credit === BalanceType::CREDIT->value) {
                        $detail->credit = floatval($detail->amount);
                        $detail->debit = 0;
                    }
                });
            } else {
                $paymentRequest->details[] = [
                    'id' => null,
                    'journalAccountInfo' => null,
                    'particulars' => 'CASH IN BANK',
                    'amount' => null,
                    'cost' => null,
                    'debit' => null,
                    'credit' => null,
                ];
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
