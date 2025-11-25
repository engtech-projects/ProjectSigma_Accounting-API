<?php

namespace App\Http\Controllers;

use App\Enums\JournalStatus;
use App\Enums\TransactionFlowName;
use App\Enums\TransactionFlowStatus;
use App\Http\Requests\JournalEntry\JournalEntryRequestFilter;
use App\Http\Requests\JournalEntry\JournalEntryRequestStore;
use App\Http\Requests\JournalEntry\JournalEntryRequestUpdate;
use App\Http\Requests\JournalEntryDetailsRequest;
use App\Http\Resources\AccountingCollections\JournalEntryCollection;
use App\Models\FiscalYear;
use App\Models\JournalEntry;
use App\Models\PaymentRequest;
use App\Models\PostingPeriod;
use App\Services\JournalEntryService;
use App\Services\TransactionFlowService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalEntryController extends Controller
{
    public function index(JournalEntryRequestFilter $request)
    {
        try {
            $validatedData = $request->validated();

            return new JsonResponse([
                'success' => true,
                'message' => 'Journal Entries Successfully Retrieved.',
                'data' => JournalEntryCollection::collection(JournalEntryService::getPaginated($validatedData)),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error retrieving journal entries.',
                'data' => null,
            ], 500);
        }
    }

    public function store(JournalEntryRequestStore $request)
    {
        if (TransactionFlowService::checkPendingFlow($request->payment_request_id, TransactionFlowName::CREATE_JOURNAL_ENTRY->value)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Transaction Flow is pending. Please complete the previous transaction flow first.',
                'data' => null,
            ], 400);
        }
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();
            $validatedData['id'] = FiscalYear::currentPostingPeriod();
            $validatedData['status'] = JournalStatus::OPEN->value;
            $validatedData['fiscal_year_id'] = FiscalYear::current()->pluck('id')->first();
            $validatedData['posting_period_id'] = PostingPeriod::where('fiscal_year_id', $validatedData['fiscal_year_id'])->pluck('id')->last();
            $validatedData['journal_date'] = PaymentRequest::find($validatedData['payment_request_id'])->request_date;
            if ($validatedData['fiscal_year_id'] == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'No open period found. Please create a new period. current period',
                    'data' => null,
                ], 400);
            }
            $validatedData['created_by'] = auth()->user()->id;
            $journalEntry = JournalEntry::create($validatedData);
            foreach ($validatedData['details'] as $detail) {
                $journalEntry->details()->create([
                    'account_id' => $detail['journalAccountInfo']['id'] ?? null,
                    'stakeholder_id' => $detail['stakeholderInformation']['id'] ?? null,
                    'description' => $detail['description'] ?? null,
                    'debit' => $detail['debit'] ?? null,
                    'credit' => $detail['credit'] ?? null,
                ]);
            }
            TransactionFlowService::updateTransactionFlow(
                $validatedData['payment_request_id'],
                TransactionFlowName::CREATE_JOURNAL_ENTRY->value,
                TransactionFlowStatus::DONE->value
            );
        });
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entry Successfully Created.',
        ], 201);
    }

    public function update(JournalEntryRequestUpdate $request)
    {
        $journalEntry = JournalEntry::find($request->id);
        $validatedData = $request->validated();
        $journalEntry->update($validatedData);
        $existingIds = $journalEntry->details()->pluck('id')->toArray();
        $journalDetails = $request->details;
        $incomingIds = [];
        foreach ($journalDetails as $journalDetail) {
            $detail = $journalEntry->details()->updateOrCreate($journalDetail);
            $incomingIds[] = $detail->id;
        }
        $toDelete = array_diff($existingIds, $incomingIds);
        $journalEntry->details()->whereIn('id', $toDelete)->delete();

        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entry Successfully Updated.',
            'data' => new JournalEntryCollection($journalEntry->load('details')),
        ], 200);
    }

    public function openEntries(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Open Journal Entries Successfully Retrieved.',
            'data' => JournalEntryService::OpenEntries($request->validated()),
        ], 200);
    }

    public function voidEntries()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Void Journal Entries Successfully Retrieved.',
            'data' => JournalEntryCollection::collection(JournalEntryService::voidEntries())->response()->getData(true),
        ], 200);
    }

    public function postedEntries(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Posted Journal Entries Successfully Retrieved.',
            'data' => JournalEntryService::postedEntries($request->validated()),
        ], 200);
    }

    public function unpostedEntries(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Unposted Journal Entries Successfully Retrieved.',
            'data' => JournalEntryService::unpostedEntries($request->validated()),
        ], 200);
    }

    public function disbursementEntries(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Journal Entries Successfully Retrieved.',
            'data' => JournalEntryService::disbursementEntries($request->validated()),
        ], 200);
    }

    public function forPaymentEntries(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entries for Payment Successfully Retrieved.',
            'data' => JournalEntryService::forPaymentEntries($request->validated()),
        ], 200);
    }

    public function CashEntries(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Journal Entries Successfully Retrieved.',
            'data' => JournalEntryService::CashEntries($request->validated()),
        ], 200);
    }

    public function forVoucherEntriesListDisbursement(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entries for Voucher Successfully Retrieved.',
            'data' => JournalEntryService::forVoucherEntriesListDisbursement($request->validated()),
        ], 200);
    }

    public function forVoucherEntriesListCash(JournalEntryRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Entries for Voucher Successfully Retrieved.',
            'data' => JournalEntryService::forVoucherEntriesListCash($request->validated()),
        ], 200);
    }

    public function generateJournalNumber()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Number Successfully Generated.',
            'data' => JournalEntryService::generateJournalNumber(),
        ], 200);
    }

    public function getAccountsVatTax()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Accounts VAT Tax Successfully Retrieved.',
            'data' => JournalEntryService::getAccountsVatTax(),
        ], 200);
    }

    public function generateJournalDetails(JournalEntryDetailsRequest $request)
    {
        $validatedData = $request->validated();
        $journalData = $request->all();
        $journalData['details'] = JournalEntryService::generateJournalDetails($validatedData['details']);

        return new JsonResponse([
            'message' => 'success',
            'data' => $journalData,
        ], 200);
    }
}
