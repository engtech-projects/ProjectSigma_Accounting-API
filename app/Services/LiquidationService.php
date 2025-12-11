<?php

namespace App\Services;

use App\Enums\PaymentRequestType;
use App\Models\PaymentRequestDetails;
use App\Models\Stakeholders\Project;

class LiquidationService
{
    public static function checkAllocation($project_id)
    {
        $total = PaymentRequestDetails::where('project_id', $project_id)
            ->where('type', PaymentRequestType::LIQUIDATION->value)
            ->sum('total');
        $projectAllocation = Project::find($project_id)->allocation;
        return $total > $projectAllocation;
    }
}
