<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollRequest\PayrollRequestFilter;
use App\Http\Resources\PayrollRequestCollection;
use App\Services\PayrollService;
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
            'data' => new PayrollRequestCollection($payrollRequests),
        ], 200);
    }

    public function myRequest()
    {
        $payrollRequests = PayrollService::myRequests();

        return new JsonResponse([
            'success' => true,
            'message' => 'Payroll Request Successfully Retrieved.',
            'data' => new PayrollRequestCollection($payrollRequests),
        ], 200);
    }

    public function myApproval()
    {
        $payrollRequests = PayrollService::myApprovals();

        return new JsonResponse([
            'success' => true,
            'message' => 'Payroll Request Successfully Retrieved.',
            'data' => new PayrollRequestCollection($payrollRequests),
        ], 200);
    }
}
