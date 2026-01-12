<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\OfficeCodeFilterRequest;

class OfficeCodeReportController extends Controller
{
    public function statementOfCashFlow(OfficeCodeFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
