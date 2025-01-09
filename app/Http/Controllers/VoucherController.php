<?php

namespace App\Http\Controllers;

use App\Enums\JournalStatus;
use App\Enums\RequestStatuses;
use App\Enums\VoucherType;
use App\Http\Requests\CashReceivedRequest;
use App\Http\Requests\Voucher\CashVoucherRequestFilter;
use App\Http\Requests\Voucher\CashVoucherRequestStore;
use App\Http\Requests\Voucher\DisbursementVoucherRequestFilter;
use App\Http\Requests\Voucher\DisbursementVoucherRequestStore;
use App\Http\Requests\Voucher\VoucherFilter;
use App\Http\Resources\VoucherResource;
use App\Models\Book;
use App\Models\CashRequest;
use App\Models\DisbursementRequest;
use App\Models\JournalEntry;
use App\Models\PaymentRequest;
use App\Models\Period;
use App\Models\PostingPeriod;
use App\Models\Voucher;
use App\Notifications\RequestCashVoucherForApprovalNotification;
use App\Notifications\RequestDisbursementVoucherForApprovalNotification;
use App\Services\JournalEntryService;
use App\Services\VoucherService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    public function index(VoucherFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Vouchers fetched',
            'data' => VoucherService::getWithPagination($request->validated()),
        ], 201);
    }

    public function disbursementAllRequest(DisbursementVoucherRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Vouchers fetched',
            'data' => VoucherService::getWithPaginationDisbursement($request->validated()),
        ], 201);
    }

    public function disbursementMyRequest(DisbursementVoucherRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Voucher My Requests Successfully Retrieved.',
            'data' => VoucherService::myRequestDisbursement(),
        ], 200);
    }

    public function disbursementMyApprovals()
    {
        $myApprovals = VoucherService::myApprovalsDisbursement();

        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Voucher My Approvals Successfully Retrieved.',
            'data' => $myApprovals,
        ], 200);
    }

    public function disbursementMyVouchering()
    {
        $myvouchering = VoucherService::myVoucheringDisbursement();

        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Voucher My Approvals Successfully Retrieved.',
            'data' => $myvouchering,
        ], 200);
    }

    public function cashAllRequest(CashVoucherRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Vouchers fetched',
            'data' => VoucherService::getWithPaginationCash($request->validated()),
        ], 201);
    }

    public function cashMyRequest()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Voucher My Requests Successfully Retrieved.',
            'data' => VoucherService::myRequestCash(),
        ], 200);
    }

    public function cashGetClearingVouchers()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Voucher My Clearing/Settlement Successfully Retrieved.',
            'data' => VoucherService::getClearingVouchersCash(),
        ], 200);
    }

    public function cashGetClearedVouchers()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Voucher My Clearing/Settlement Successfully Retrieved.',
            'data' => VoucherService::getClearedVouchersCash(),
        ], 200);
    }

    public function cashMyApprovals()
    {
        $myApprovals = VoucherService::myApprovalsCash();

        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Voucher My Approvals Successfully Retrieved.',
            'data' => $myApprovals,
        ], 200);
    }

    public function createCash(CashVoucherRequestStore $request)
    {
        DB::beginTransaction();
        // try {
            $validatedData = $request->validated();
            $paymentRequestId = PaymentRequest::where('prf_no', $validatedData['reference_no'])->first()->id;
            $journalEntry = JournalEntry::create(
                [
                    'journal_no' => JournalEntryService::generateJournalNumber(),
                    'entry_date' => Carbon::now(),
                    'journal_date' => Carbon::now(),
                    'status' => JournalStatus::UNPOSTED->value,
                    'posting_period_id' => PostingPeriod::currentPostingPeriod(),
                    'period_id' => Period::current()->pluck('id')->first(),
                    'reference_no' => $validatedData['reference_no'],
                    'payment_request_id' => $paymentRequestId,
                    'remarks' => $validatedData['particulars'],
                ]
            );
            $validatedData['type'] = VoucherType::CASH->value;
            $validatedData['book_id'] = Book::where('code', VoucherType::CASH_CODE->value)->first()->id;
            $validatedData['date_encoded'] = Carbon::now();
            $validatedData['request_status'] = RequestStatuses::PENDING->value;
            $validatedData['created_by'] = auth()->user()->id;
            $voucher = CashRequest::create($validatedData);
            foreach ($validatedData['details'] as $detail) {
                $voucher->details()->create([
                    'account_id' => $detail['account_id'],
                    'stakeholder_id' => $detail['stakeholder_id'] ?? null,
                    'description' => $detail['description'] ?? null,
                    'debit' => $detail['debit'] ?? null,
                    'credit' => $detail['credit'] ?? null,
                ]);
                $journalEntry->details()->create([
                    'account_id' => $detail['account_id'],
                    'stakeholder_id' => $detail['stakeholder_id'] ?? null,
                    'description' => $detail['description'] ?? null,
                    'debit' => $detail['credit'] ?? null,
                    'credit' => $detail['debit'] ?? null,
                ]);
            }
            $voucher->journalEntry()->update([
                'entry_date' => $validatedData['voucher_date'],
            ]);
            DB::commit();
            $voucher->notify(new RequestCashVoucherForApprovalNotification(auth()->user()->token, $voucher));

            return new JsonResponse([
                'success' => true,
                'message' => 'Voucher created',
                'data' => $voucher,
            ], 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return new JsonResponse([
        //         'success' => false,
        //         'message' => 'Voucher creation failed',
        //     ], 500);
        // }
    }

    public function cashReceived(CashReceivedRequest $request)
    {
        DB::beginTransaction();
        // try {
            $validatedData = $request->validated();
            $voucher = CashRequest::findOrFail($validatedData['voucher_id']);
            $voucher->update([
                'received_by' => $validatedData['received_by'],
                'received_date' => $validatedData['received_date'],
                'receipt_no' => $validatedData['receipt_no'],
                'attach_file' => $validatedData['attach_file'] ?? null,
            ]);

            JournalEntry::where('payment_request_id', $voucher->journalEntry->payment_request_id)->update([
                'status' => JournalStatus::POSTED->value,
            ]);

            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Voucher Updated',
                'data' => $voucher,
            ], 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return new JsonResponse([
        //         'success' => false,
        //         'message' => 'Voucher creation failed',
        //     ], 500);
        // }
    }

    public function createDisbursement(DisbursementVoucherRequestStore $request)
    {
        DB::beginTransaction();
        // try {
        $validatedData = $request->validated();
        $validatedData['type'] = VoucherType::DISBURSEMENT->value;
        $validatedData['book_id'] = Book::where('code', VoucherType::DISBURSEMENT_CODE->value)->first()->id;
        $validatedData['date_encoded'] = Carbon::now();
        $validatedData['request_status'] = RequestStatuses::PENDING->value;
        $validatedData['created_by'] = auth()->user()->id;
        $voucher = DisbursementRequest::create($validatedData);
        foreach ($validatedData['details'] as $detail) {
            $voucher->details()->create([
                'account_id' => $detail['account_id'],
                'stakeholder_id' => $detail['stakeholder_id'] ?? null,
                'description' => $detail['description'] ?? null,
                'debit' => $detail['debit'] ?? null,
                'credit' => $detail['credit'] ?? null,
            ]);
        }
        $journalEntry = JournalEntry::find($validatedData['journal_entry_id']);
        $journalEntry->update([
            'entry_date' => $validatedData['voucher_date'],
        ]);
        DB::commit();
        $voucher->notify(new RequestDisbursementVoucherForApprovalNotification(auth()->user()->token, $voucher));

        return new JsonResponse([
            'success' => true,
            'message' => 'Voucher created',
            'data' => $voucher,
        ], 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return new JsonResponse([
        //         'success' => false,
        //         'message' => 'Voucher creation failed',
        //     ], 500);
        // }
    }

    public function voucherNo($prefix = 'DV')
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Voucher number generated',
                'data' => Voucher::generateVoucherNo($prefix),
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Voucher number generation failed',
            ], 500);
        }
    }

    public function disbursementGenerateVoucherNumber()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Voucher number generated',
            'data' => VoucherService::generateVoucherNo('DV'),
        ], 201);
    }

    public function cashGenerateVoucherNumber()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Voucher number generated',
            'data' => VoucherService::generateVoucherNo('CV'),
        ], 201);
    }

    public function show($id)
    {
        $voucher = Voucher::withPaymentRequestDetails()
            ->orderDesc()
            ->find($id);

        return new JsonResponse([
            'success' => true,
            'message' => 'Voucher Successfully Retrieved.',
            'data' => new VoucherResource($voucher),
        ], 200);
    }
}
