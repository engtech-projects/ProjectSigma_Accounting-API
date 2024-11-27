<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;
use App\Services\InventoryServices;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public static function syncAll()
    {
        try{
            $syncSupplier = InventoryServices::syncSupplier(auth()->user()->token);
            return new JsonResponse([
            'success' => true,
            'message' => 'Supplier Successfully Retrieved.',
                'total_inserted' => [
                    'supplier' => $syncSupplier,
                ],
            ], 200);
        }catch(\Exception $e){
            return new JsonResponse([
                'success' => false,
                'message' => 'Supplier sync failed',
            ], 500);
        }
    }
    public function supplier()
    {
        $syncSupplier = InventoryServices::syncSupplier(auth()->user()->token);
        if( $syncSupplier ){
            return new JsonResponse([
                'success' => true,
                'message' => 'Supplier Successfully Retrieved.',
                'total_inserted' => $syncSupplier,
            ], 200);
        }else{
            return new JsonResponse([
                'success' => false,
                'message' => 'Supplier sync failed',
            ], 500);
        }
    }
}
