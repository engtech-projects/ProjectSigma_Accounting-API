<?php

namespace App\Http\Controllers;

use App\Services\InventoryServices;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function supplier()
    {
        $supplier = InventoryServices::syncSupplier(auth()->user()->token);
        if( $supplier ){
            return response()->json(['message' => 'Supplier synced successfully']);
        }else{
            return response()->json(['message' => 'Supplier sync failed']);
        }
    }
}
