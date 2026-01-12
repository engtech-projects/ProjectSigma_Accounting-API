<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\StatementOfCashFlowFilterRequest;

class StatementOfCashFlowReportController extends Controller
{
    public function statementOfCashFlow(StatementOfCashFlowFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
