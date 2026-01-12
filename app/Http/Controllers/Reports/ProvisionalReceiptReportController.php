<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ProvisionalReceiptFilterRequest;

class ProvisionalReceiptReportController extends Controller
{
    public function statementOfCashFlow(ProvisionalReceiptFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
