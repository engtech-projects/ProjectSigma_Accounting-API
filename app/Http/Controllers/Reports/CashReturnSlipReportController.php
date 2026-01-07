<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\CashReturnSlipFilterRequest;

class CashReturnSlipReportController extends Controller
{
    public function bookBalance(CashReturnSlipFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
