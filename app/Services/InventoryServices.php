<?php

namespace App\Services;

use App\Models\Stakeholders\Supplier;
use Http;

class InventoryServices
{
    public static function syncSupplier($token)
    {
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.inventory_api_url')."/api/supplier/list");
            if (!$response->successful()) {
                return false;
            }
            $suppliers = $response->json()['data'];
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
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

