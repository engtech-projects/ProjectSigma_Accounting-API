<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReplenishmentSummaryFilterRequest;

class ReplenishmentSummaryReportController extends Controller
{
    public function statementOfCashFlow(ReplenishmentSummaryFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
