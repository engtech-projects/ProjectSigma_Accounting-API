<?php

namespace App\Services;

use App\Models\Stakeholders\Supplier;
use DB;
use Http;

class InventoryServices
{
    public static function syncSupplier($token)
    {
        DB::beginTransaction();
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.inventory_api_url').'/api/supplier/list');
        if (! $response->successful()) {
            return false;
        }
        $suppliers = $response->json()['data'];
        $totalSupplierCount = Supplier::count();
        $total_inserted = 0;
        foreach ($suppliers as $supplier) {
            $supplier_model = Supplier::updateOrCreate(
                [
                    'id' => $supplier['id'],
                    'source_id' => $supplier['id'],
                ],
                [
                    'name' => $supplier['name'],
                ]
            );
            $supplier_model->stakeholder()->updateOrCreate(
                [
                    'stakeholdable_type' => Supplier::class,
                    'stakeholdable_id' => $supplier['id'],
                ],
                [
                    'name' => $supplier['name'],
                ]
            );
        }
        DB::commit();
        $total_inserted = Supplier::count() - $totalSupplierCount;

        return $total_inserted;
    }
}
