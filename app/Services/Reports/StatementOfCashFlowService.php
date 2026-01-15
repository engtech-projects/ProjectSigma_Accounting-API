<?php

namespace App\Services\Reports;

use App\Http\Resources\Reports\StatementOfCashFlowReportResource;

class StatementOfCashFlowService
{
    public static function statementOfCashFlowReport($startDate, $endDate)
    {
        return StatementOfCashFlowReportResource::make([]);
    }
}
