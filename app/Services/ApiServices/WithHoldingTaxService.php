<?php

namespace App\Services;

use App\Models\WithHoldingTax;

class WithHoldingTaxService
{
    public static function getPaginated(array $filters = [])
    {
        $query = WithHoldingTax::query();

        $query->with(['accounts', 'stakeholder'])
            ->orderByDesc('created_at');

        return $query->paginate(config('services.pagination.limit'));
    }
}
