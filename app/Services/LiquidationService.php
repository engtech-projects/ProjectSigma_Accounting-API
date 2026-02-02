<?php

namespace App\Services;

use App\Enums\PaymentRequestType;
use App\Models\PaymentRequest;
use App\Models\Stakeholders\Project;

class LiquidationService
{
    public static function checkAllocation(int $projectId): bool
    {
        $project = Project::find($projectId);

        if (! $project) {
            return false;
        }

        $total = PaymentRequest::where('type', PaymentRequestType::LIQUIDATION->value)
            ->whereHas('details', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->sum('total');

        $projectAllocation = $project->allocation ?? 0;

        return $total > $projectAllocation;
    }
}
