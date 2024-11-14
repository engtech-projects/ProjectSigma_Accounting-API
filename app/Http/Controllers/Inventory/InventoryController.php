<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;
use App\Services\InventoryServices;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function supplier()
    {
        $supplier = InventoryServices::syncSupplier(auth()->user()->token);
        if( $supplier ){
            return new JsonResponse([
                'success' => true,
                'message' => 'Supplier Successfully Retrieved.',
            ], 200);
        }else{
            return new JsonResponse([
                'success' => false,
                'message' => 'Supplier sync failed',
            ], 500);
        }
    }
}
