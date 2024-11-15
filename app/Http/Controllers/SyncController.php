<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Hrms\HrmsController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Projects\ProjectController;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SyncController extends Controller
{
    public function syncAll()
    {
        DB::beginTransaction();
        //try {
            $syncHrms = HrmsController::syncAll();
            $syncProject = ProjectController::syncAll();
            $syncInventory = InventoryController::syncAll();
            return new JsonResponse([
                'success' => true,
                'message' => 'All Data Successfully Retrieved.',
                'total_inserted' => [
                    'hrms' => $syncHrms,
                    'project' => $syncProject,
                    'inventory' => $syncInventory,
                ]
            ], 200);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return new JsonResponse([
        //         'success' => false,
        //         'message' => 'All Data Failed to Retrieve.',
        //     ], 500);
        // }
    }
}
