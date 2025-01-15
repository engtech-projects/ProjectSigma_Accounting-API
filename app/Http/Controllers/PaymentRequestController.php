<?php

namespace App\Http\Controllers;

use App\Enums\PaymentRequestType;
use App\Enums\PrefixType;
use App\Enums\RequestStatuses;
use App\Http\Requests\PaymentRequest\PaymentRequestFilter;
use App\Http\Requests\PaymentRequest\PaymentRequestStore;
use App\Http\Requests\PaymentRequest\PaymentRequestUpdate;
use App\Http\Requests\PayrollPaymentRequest;
use App\Http\Requests\Stakeholder\StakeholderRequestFilter;
use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Http\Resources\PaymentRequestResource;
use App\Models\PaymentRequest;
use App\Models\StakeHolder;
use App\Notifications\RequestPaymentForApprovalNotification;
use App\Services\PaymentServices;
use App\Services\StakeHolderService;
use DB;
use Illuminate\Http\JsonResponse;

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

    public function myRequest()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Payment My Requests Successfully Retrieved.',
            'data' => PaymentServices::myRequests(),
        ], 200);
    }

    public function myApprovals()
    {
        $myApprovals = PaymentServices::myApprovals();

        return new JsonResponse([
            'success' => true,
            'message' => 'Payment My Approvals Successfully Retrieved.',
            'data' => $myApprovals,
        ], 200);
    }

    public function searchStakeHolders(StakeholderRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Stakeholders Successfully Retrieved.',
            'data' => StakeHolderService::searchStakeHolders($request->validated()),
        ], 200);
    }

    public function store(PaymentRequestStore $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $prfNo = PaymentServices::generatePrfNo(PrefixType::RFA_ACCTG->value);
            $validatedData['prf_no'] = $prfNo;
            $validatedData['type'] = PaymentRequestType::PRF->value;
            $validatedData['stakeholder_id'] = $validatedData['stakeholderInformation']['id'] ?? null;
            $validatedData['created_by'] = auth()->user()->id;
            $validatedData['request_status'] = RequestStatuses::PENDING->value;
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
            $paymentRequest->notify(new RequestPaymentForApprovalNotification(auth()->user()->token, $paymentRequest));
            DB::commit();

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
                'prf_no' => PaymentServices::generatePrfNo(PrefixType::RFA_PAYROLL->value),
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
            'data' => PaymentServices::generatePrfNo(PrefixType::RFA_ACCTG->value),
        ], 200);
    }
}
