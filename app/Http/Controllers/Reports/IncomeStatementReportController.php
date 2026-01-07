<?php

namespace App\Http\Reports\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Reports\Requests\IncomeStatementFilterRequest;

class IncomeStatementReportController extends Controller
{
    public function incomeStatement(IncomeStatementFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
