<?php

namespace App\Services\ApiServices;

use App\Models\WithHoldingTax;

class WithHoldingTaxService
{
    public static function getPaginated(array $filters = [])
    {
        $query = WithHoldingTax::query();

        $query->with(['account'])
            ->orderBy('created_at', 'ASC');

        return $query->paginate(config('services.pagination.limit'));
    }

    public static function getWithHoldingTax($id)
    {
        $withholdingTax = WithHoldingTax::find($id);

        return [
            'id' => $withholdingTax->id,
            'information' => $withholdingTax,
        ];
    }
}
