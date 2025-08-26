<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Enums\PrefixType;
use App\Enums\RequestStatuses;
use App\Enums\TransactionLogStatus;
use App\Http\Requests\PaymentRequest\PaymentRequestFilter;
use App\Http\Requests\PaymentRequest\PaymentRequestStore;
use App\Http\Requests\PaymentRequest\PaymentRequestUpdate;
use App\Http\Requests\PayrollPaymentRequest;
use App\Http\Requests\Stakeholder\StakeholderRequestFilter;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Models\PaymentRequest;
use App\Models\StakeHolder;
use App\Models\TransactionFlow;
use App\Models\TransactionFlowModel;
use App\Models\TransactionLog;
use App\Notifications\RequestPaymentForApprovalNotification;
use App\Services\PaymentServices;
use App\Services\StakeHolderService;
use App\Services\TransactionFlowService;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;
use Str;

class PaymentRequestController extends Controller
{
    public function index(PaymentRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Payment Requests Successfully Retrived.',
            'data' => PaymentServices::getWithPagination($request->validated()),
        ], 200);
    }

    public function myRequest(PaymentRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Payment My Requests Successfully Retrieved.',
            'data' => PaymentServices::myRequests($request->validated()),
        ], 200);
    }

    public function myApprovals(PaymentRequestFilter $request)
    {
        $myApprovals = PaymentServices::myApprovals($request->validated());

        return new JsonResponse([
            'success' => true,
            'message' => 'Payment My Approvals Successfully Retrieved.',
            'data' => $myApprovals,
        ], 200);
    }

    public function myDeniedRequests(PaymentRequestFilter $request)
    {
        $validatedData = $request->validated();
        $paymentRequest = PaymentRequest::when(isset($validatedData['key']), function ($query, $key) use ($validatedData) {
            return $query->where('prf_no', 'LIKE', "%{$validatedData['key']}%")
                ->orWhereHas('stakeholder', function ($query) use ($validatedData) {
                    $query->where('name', 'LIKE', "%{$validatedData['key']}%");
                });
        })
            ->myDeniedRequest()
            ->withStakeholder()
            ->orderByDesc('created_at')
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));

        return PaymentRequestCollection::collection($paymentRequest)
            ->additional([
                'success' => true,
                'message' => 'Payment My Denied Requests Successfully Retrieved.',
            ]);
    }

    public function searchStakeHolders(StakeholderRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholders Successfully Retrieved.',
            'data' => StakeHolderService::searchStakeHolders($request->validated()),
        ], 200);
    }

    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'attachment_files' => 'required|array',
            'attachment_files.*' => 'file',
        ]);

        $encryptedFileNames = [];

        if ($request->hasFile('attachment_files')) {
            foreach ($request->file('attachment_files') as $file) {
                $encryptedFileName = Str::random(40).'.'.$file->getClientOriginalExtension();
                $file->storeAs('temp/', $encryptedFileName);
                $encryptedFileNames[] = $encryptedFileName;
            }
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Attachment File Successfully Uploaded.',
            'data' => $encryptedFileNames,
        ], 200);
    }

    public function store(PaymentRequestStore $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $prfNo = PaymentServices::generatePrfNo('PRF-'.auth()->user()->department_code);
            $validatedData['prf_no'] = $prfNo;
            $validatedData['type'] = PaymentRequestType::PRF->value;
            $validatedData['stakeholder_id'] = $validatedData['stakeholderInformation']['id'] ?? null;
            $validatedData['created_by'] = auth()->user()->id;
            $validatedData['request_status'] = RequestStatuses::PENDING->value;
            $validatedData['attachment_url'] = json_encode($request->attachment_file_names);
            $paymentRequest = PaymentRequest::create($validatedData);
            foreach ($validatedData['details'] as $detail) {
                $paymentRequest->details()->create([
                    'particulars' => $detail['particulars'] ?? null,
                    'cost' => $detail['cost'] ?? null,
                    'vat' => $detail['vat'] ?? null,
                    'amount' => $detail['amount'] ?? null,
                    'stakeholder_id' => $detail['stakeholderInformation']['id'] ?? null,
                    'particular_group_id' => $detail['particularGroup']['id'] ?? null,
                    'total_vat_amount' => $detail['total_vat_amount'] ?? null,
                ]);
            }
            $transactionFlowData = TransactionFlowService::getTransactionFlow(
                PaymentRequestType::PRF->value,
                $paymentRequest->id
            );
            if (!empty($transactionFlowData)) {
                // Uses the HasMany relation so timestamps and observers apply
                $paymentRequest->transactionFlow()->createMany($transactionFlowData);
            }
            TransactionLog::query()->create([
                'type'             => TransactionLogStatus::REQUEST->value,
                'transaction_code' => $paymentRequest->prf_no,
                'description'      => 'Payment Request Created',
                'created_by'       => auth()->user()->id,
            ]);
            $paymentRequest->notify(new RequestPaymentForApprovalNotification(auth()->user()->token, $paymentRequest));
            DB::commit();
            foreach ($request->attachment_file_names as $file) {
                $path = 'prf/'.$paymentRequest->id.'/'.$file;
                Storage::move('temp/'.$file, $path);
            }
            return new JsonResponse([
                'success' => true,
                'message' => 'Payment Request Created Successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Payment Request Creation Failed',
            ], 500);
        }
    }

    public function show($id)
    {
        $paymentRequest = PaymentRequest::withDetails()
            ->withStakeholder()
            ->find($id);

        return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request Successfully Retrieved.',
            'data' => new PaymentRequestCollection($paymentRequest),
        ], 200);
    }

    public function update(PaymentRequestUpdate $request, $id)
    {
        $paymentRequest = PaymentRequest::findOrFail($id);
        $paymentRequest->update($request->validated());
        $existingIds = $paymentRequest->details()->pluck('id')->toArray();
        $paymentRequestDetails = $request->details;
        $incomingIds = [];
        foreach ($paymentRequestDetails as $paymentRequestDetail) {
            $detail = $paymentRequest->details()->updateOrCreate($paymentRequestDetail);
            $incomingIds[] = $detail->id;
        }
        $toDelete = array_diff($existingIds, $incomingIds);
        $paymentRequest->details()->whereIn('id', $toDelete)->delete();

        return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request Successfully Updated.',
            'data' => new PaymentRequestCollection($paymentRequest->load(['stakeholder', 'details.stakeholder'])),
        ], 200);
    }

    public function journalPaymentRequestEntries()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Journal Payment Request Entries Successfully Retrieved.',
            'data' => PaymentServices::journalPaymentRequestEntries(),
        ], 200);
    }

    public function createPayrollRequest(PayrollPaymentRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $paymentRequest = PaymentRequest::create([
                'prf_no' => PaymentServices::generatePrfNo(PrefixType::PRF_PAYROLL->value),
                'type' => PaymentRequestType::PAYROLL->value,
                'request_status' => RequestStatuses::PENDING->value,
                'description' => $validatedData['description'],
                'request_date' => $validatedData['request_date'],
                'total' => $validatedData['total'],
                'total_vat_amount' => 0,
                'created_by' => $validatedData['created_by'],
                'stakeholder_id' => StakeHolder::findIdByNameOrNull($validatedData['stakeholder_id']),
            ]);
            $validatedDataDetails = $validatedData['details'];
            foreach ($validatedDataDetails as $detail) {
                $paymentRequest->details()->create([
                    'stakeholder_id' => StakeHolder::findIdByNameOrNull($detail['id']),
                    'particulars' => $detail['account'],
                    'cost' => $detail['cost'] ?? null,
                    'vat' => 0,
                    'amount' => $detail['cost'] ?? null,
                    'total_vat_amount' => 0,
                ]);
            }

            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Payroll Request Successfully Created',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Payroll Request Creation Failed',
            ], 500);
        }
    }

    public function generatePrfNo()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Payment Request No Successfully Generated.',
            'data' => PaymentServices::generatePrfNo('PRF-'.auth()->user()->department_code),
        ], 200);
    }
}
