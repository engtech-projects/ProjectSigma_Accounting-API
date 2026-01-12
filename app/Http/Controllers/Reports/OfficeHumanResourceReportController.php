<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\OfficeHumanResourceFilterRequest;

class OfficeHumanResourceReportController extends Controller
{
    public function statementOfCashFlow(OfficeHumanResourceFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
