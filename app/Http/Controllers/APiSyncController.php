<?php

namespace App\Http\Controllers;

use App\Jobs\ApiHrmsSyncJob;
use App\Jobs\ApiInventorySyncJob;
use App\Jobs\ApiProjectsSyncJob;
use App\Services\ApiServices\HrmsService;
use App\Services\ApiServices\ProjectMonitoringService;
use App\Services\ApiServices\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class APiSyncController extends Controller
{
    public function syncAll(Request $request)
    {
        DB::transaction(function () {
            $hrmsService = new HrmsService();
            $projectService = new ProjectMonitoringService();
            $inventoryService = new InventoryService();
            if (! $hrmsService->syncAll() || ! $inventoryService->syncAll() || ! $projectService->syncAll()) {
                throw new \Exception('HRMS sync failed.');
            }
        });
        return response()->json([
            'message' => 'Successfully synced with api services.',
            'success' => true,
        ]);
    }

    public function syncEmployees(Request $request)
    {
        DB::transaction(function () {
            ApiHrmsSyncJob::dispatch('syncEmployees');
        });
        return response()->json([
            'message' => 'Successfully synced all employees.',
            'success' => true,
        ]);
    }

    public function syncDepartments(Request $request)
    {
        DB::transaction(function () {
            ApiHrmsSyncJob::dispatch('syncDepartments');
        });
        return response()->json([
            'message' => 'Successfully synced all departments.',
            'success' => true,
        ]);
    }

    public function syncUsers(Request $request)
    {
        DB::transaction(function () {
            ApiHrmsSyncJob::dispatch('syncUsers');
        });
        return response()->json([
            'message' => 'Successfully synced all Users.',
            'success' => true,
        ]);
    }

    public function syncProjects(Request $request)
    {
        DB::transaction(function () {
            ApiProjectsSyncJob::dispatch('syncProjects');
        });
        return response()->json([
            'message' => 'Successfully synced all projects.',
            'success' => true,
        ]);
    }

    public function syncSuppliers(Request $request)
    {
        DB::transaction(function () {
            ApiInventorySyncJob::dispatch('syncSuppliers');
        });
        return response()->json([
            'message' => 'Successfully synced all suppliers.',
            'success' => true,
        ]);
    }
}
