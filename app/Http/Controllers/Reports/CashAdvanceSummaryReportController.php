<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\CashAdvanceSummaryFilterRequest;

class CashAdvanceSummaryReportController extends Controller
{
    public function bookBalance(CashAdvanceSummaryFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
