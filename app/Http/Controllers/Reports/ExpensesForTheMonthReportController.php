<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ExpenseForTheMonthFilterRequest;

class ExpensesForTheMonthReportController extends Controller
{
    public function bookBalance(ExpenseForTheMonthFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
