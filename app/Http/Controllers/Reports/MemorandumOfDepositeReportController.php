<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\MemorandumOfDepositeFilterRequest;

class MemorandumOfDepositeReportController extends Controller
{
    public function statementOfCashFlow(MemorandumOfDepositeFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
