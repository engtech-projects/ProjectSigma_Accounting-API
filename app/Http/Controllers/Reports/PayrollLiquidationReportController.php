<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\PayrollLiquidationFilterRequest;

class PayrollLiquidationReportController extends Controller
{
    public function statementOfCashFlow(PayrollLiquidationFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
