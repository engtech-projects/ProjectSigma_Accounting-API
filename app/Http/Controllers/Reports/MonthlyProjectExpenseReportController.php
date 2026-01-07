<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\MonthlyProjectExpenseFilterRequest;

class MonthlyProjectExpenseReportController extends Controller
{
    public function statementOfCashFlow(MonthlyProjectExpenseFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
