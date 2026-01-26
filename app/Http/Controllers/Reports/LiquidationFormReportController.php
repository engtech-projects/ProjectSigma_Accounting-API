<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\LiquidationFormFilterRequest;

class LiquidationFormReportController extends Controller
{
    public function statementOfCashFlow(LiquidationFormFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
