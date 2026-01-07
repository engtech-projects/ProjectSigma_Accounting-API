<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\MonthlyUnliquidatedCashAdvanceFilterRequest;

class MonthlyUnliquidatedCashAdvanceReportController extends Controller
{
    public function statementOfCashFlow(MonthlyUnliquidatedCashAdvanceFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
