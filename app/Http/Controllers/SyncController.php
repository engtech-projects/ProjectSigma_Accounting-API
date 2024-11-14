<?php

namespace App\Http\Controllers;

use App\Services\HrmsServices;
use App\Services\InventoryServices;
use App\Services\ProjectServices;
use DB;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function syncAll()
    {
        DB::beginTransaction();
        try {
            HrmsServices::syncAll();
            ProjectServices::syncAll();
            InventoryServices::syncAll();
            return response()->json([
                'success' => true,
                'message' => 'All Data Successfully Retrieved.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'All Data Failed to Retrieve.',
            ], 500);
        }
    }
}
