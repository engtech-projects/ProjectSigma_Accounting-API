<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalPayementRequestType;
use App\Enums\PaymentRequestType;
use App\Enums\PrefixType;
use App\Enums\RequestStatuses;
use App\Http\Requests\CreatePayrollRequest;
use App\Http\Requests\PayrollRequest\PayrollRequestFilter;
use App\Models\PaymentRequest;
use App\Services\ApiServices\HrmsService;
use App\Services\PaymentServices;
use App\Services\PayrollService;
use App\Services\StakeHolderService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;

class PayrollRequestController extends Controller
{
    public function index(PayrollRequestFilter $request)
    {
        $validatedData = $request->validated();
        $payrollRequests = PayrollService::withPagination($validatedData);

        return new JsonResponse([
            'success' => true,
            'message' => 'Payroll Request Successfully Retrieved.',
            'data' => $payrollRequests,
        ], 200);
    }

    public function createPayrollRequest(CreatePayrollRequest $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken, $request) {
            $validatedData = $request->validated();
            $hrmsService = new HrmsService($authToken);
            $approvalList = $hrmsService->getApprovalName(ApprovalPayementRequestType::APPROVAL_PAYMENT_REQUEST_PAYROLL->value);
            $details = collect($validatedData['details'])->map(function ($detail) {
                $stakeholderId = StakeHolderService::findIdByNameOrNull($detail['stakeholder'] ?? '');

                return [
                    'particulars' => $detail['particular'],
                    'cost' => $detail['amount'],
                    'vat' => 0,
                    'stakeholder_id' => $stakeholderId,
                    'amount' => $detail['amount'],
                    'total_vat_amount' => 0,
                    'particular_group_id' => null,
                ];
            });
            $paymentRequest = PaymentRequest::create([
                'stakeholder_id' => StakeHolderService::findIdByNameOrNull($validatedData['payee'] ?? ''),
                'prf_no' => PaymentServices::generatePrfNo(PrefixType::PRF_PAYROLL->value),
                'request_date' => Carbon::now(),
                'description' => $validatedData['remarks'],
                'total' => $validatedData['amount'],
                'approvals' => $approvalList,
                'type' => PaymentRequestType::PAYROLL->value,
                'created_by' => $validatedData['requested_by'],
                'request_status' => RequestStatuses::APPROVED->value,
                'total_vat_amount' => 0,
                'attachment_url' => null,
            ]);
            $paymentRequest->details()->createMany($details->toArray());
        });

        return new JsonResponse([
            'success' => true,
            'message' => 'Payroll Request Saved.',
        ], 200);
    }
}
