<?php

namespace App\Services;

use App\Enums\JournalStatus;
use App\Http\Resources\AccountingCollections\JournalEntryCollection;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\JournalEntry;
use Carbon\Carbon;

class JournalEntryService
{
    public static function getPaginated($validateData)
    {
        $query = JournalEntry::query();
        if (isset($validateData['status'])) {
            $query->status($validateData['status']);
        }

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function OpenEntries(array $validatedData)
    {
        $journalRequest = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })

            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->openJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($journalRequest)->response()->getData(true);
    }

    public static function voidEntries()
    {
        return JournalEntry::where('status', JournalStatus::VOID->value)
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->voidJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));
    }

    public static function postedEntries(array $validatedData)
    {
        $journalRequest = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })

            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->postedJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($journalRequest)->response()->getData(true);
    }

    public static function unpostedEntries(array $validatedData)
    {
        $journalRequest = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->unpostedJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($journalRequest)->response()->getData(true);
    }

    public static function draftedEntries()
    {
        return JournalEntry::where('status', JournalStatus::DRAFTED->value)
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->draftedJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));
    }

    public static function forVoucherEntriesListDisbursement(array $validatedData)
    {
        $jounalEntries = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->openJournals()
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($jounalEntries)->response()->getData(true);
    }

    public static function forVoucherEntriesListCash(array $validatedData)
    {
        $journalEntries = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->where('status', JournalStatus::UNPOSTED->value)
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($journalEntries)->response()->getData(true);
    }

    public static function disbursementEntries(array $validatedData)
    {
        $jounalEntries = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->forDisbursementJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($jounalEntries)->response()->getData(true);
    }

    public static function forPaymentEntries(array $validatedData)
    {
        $jounalEntries = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->forPaymentJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($jounalEntries)->response()->getData(true);
    }

    public static function CashEntries(array $validatedData)
    {
        $journalEntries = JournalEntry::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('journal_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('paymentRequest.stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })

            ->withPaymentRequestDetails()
            ->withAccounts()
            ->withDetails()
            ->withVoucher()
            ->forPaymentJournals()
            ->orderByDesc('created_at')
            ->paginate(config('services.pagination.limit'));

        return JournalEntryCollection::collection($journalEntries)->response()->getData(true);
    }

    public static function generateJournalDetails($details)
    {
        $journalData = collect($details)->map(function ($detail) {
            return [
                'stakeholder_id' => $detail['stakeholder_id'],
                'stakeholder' => $detail['stakeholder'],
                'journal_date' => $detail['journal_date'],
                'reference_no' => $detail['reference_no'],
                'payment_request_id' => $detail['payment_request_id'],
                'description' => $detail['description'],
                'remarks' => $detail['remarks'],
                'total' => $detail['total'],
            ];
        });

        return $journalData;
    }

    public static function generateJournalNumber(): string
    {
        $prefix = strtoupper('JE');
        $currentYearMonth = Carbon::now()->format('Y-m');
        // Find the highest series
        $lastJournal = JournalEntry::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();
        if ($lastJournal) {
            $lastSeries = (int) substr($lastJournal->journal_no, -4); // Get last 4 digits
            $nextSeries = $lastSeries + 1;
            // Format the series number to be 4 digits (e.g., 0001)
            $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
            // Construct the new reference number
            $journalNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";
        } else {
            $journalNo = "{$prefix}-{$currentYearMonth}-0001";
        }

        return $journalNo;
    }

    public static function getAccountsVatTax()
    {
        return Account::whereIn('account_name', AccountType::case())->get();
    }
}
