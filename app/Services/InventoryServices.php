<?php

namespace App\Services;

use App\Models\Stakeholders\Supplier;
use DB;
use Http;
use Symfony\Component\HttpFoundation\JsonResponse;

class InventoryServices
{
    public static function syncAll()
    {
        try{
            self::syncSupplier(auth()->user()->token);
            return new JsonResponse([
                'success' => true,
                'message' => 'Supplier Successfully Retrieved.',
            ], 200);
        }catch(\Exception $e){
            return new JsonResponse([
                'success' => false,
                'message' => 'Supplier sync failed',
            ], 500);
        }
    }
    public static function syncSupplier($token)
    {
        DB::beginTransaction();
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.inventory_api_url')."/api/supplier/list");
            if (!$response->successful()) {
                return false;
            }
            $suppliers = $response->json()['data'];
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
                if ($supplier_model->stakeholder()->updateOrCreate(
                    [
                        'stakeholdable_type' => Supplier::class,
                        'stakeholdable_id' => $supplier['id'],
                    ],
                    [
                        'name' => $supplier['name'],
                    ]
                )) {
                    $total_inserted++;
                }
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'Supplier Successfully Retrieved.',
                'total_inserted' => $total_inserted,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}

