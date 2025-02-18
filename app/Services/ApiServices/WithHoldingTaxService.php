<?php

namespace App\Services\ApiServices;

use App\Models\WithHoldingTax;

class WithHoldingTaxService
{
    public static function getPaginated(array $filters = [])
    {
        $query = WithHoldingTax::query();

        $query->with(['account'])
            ->orderByDesc('created_at');

        return $query->paginate(config('services.pagination.limit'));
    }
}
