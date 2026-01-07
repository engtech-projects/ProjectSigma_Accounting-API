<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\BookBalanceFilterRequest;

class BookBalanceReportController extends Controller
{
    public function bookBalance(BookBalanceFilterRequest $request)
    {
        $validatedData = $request->validated();
        return $validatedData;
    }
}
