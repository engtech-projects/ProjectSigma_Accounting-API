<?php

namespace App\Http\Controllers;

use App\Http\Services\ApiServices\ProjectMonitoringSecretKeyService;
use App\Http\Services\ApiServices\InventorySecretkeyService;
use App\Http\Services\ApiServices\HrmsSecretKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\ApiHrmsSyncJob;
use App\Jobs\ApiInventorySyncJob;
use App\Jobs\ApiProjectsSyncJob;

class ApiSyncController extends Controller
{
    public function syncAll(Request $request)
    {
        DB::transaction(function () {
            $projectService = new ProjectMonitoringSecretKeyService();
            $inventoryService = new InventorySecretkeyService();
            $hrmsService = new HrmsSecretKeyService();

            $errorServices = [];
            if (!$projectService->syncAll()) {
                $errorServices[] = "Project Monitoring";
            }
            if (!$hrmsService->syncAll()) {
                $errorServices[] = "HRMS";
            }
            if (!$inventoryService->syncAll()) {
                $errorServices[] = "Inventory";
            }
            if (!empty($errorServices)) {
                throw new \Exception('Sync with ' . implode(', ', $errorServices) .' failed while trying to sync with all API Services');
            }
        });
        return response()->json([
            'message' => 'Successfully synced with all API services.',
            'success' => true,
        ]);
    }
    //HRMS
    public function syncAllHrms(Request $request)
    {
        try {
            ApiHrmsSyncJob::dispatch('syncAllHrms');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch HRMS sync job', ['error' => $e->getMessage()]);
            throw new \Exception("HRMS sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all HRMS.',
            'success' => true,
        ]);
    }
    public function syncEmployees(Request $request)
    {
        try {
            ApiHrmsSyncJob::dispatch('syncEmployees');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Employee sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Employee sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all employees.',
            'success' => true,
        ]);
    }
    public function syncDepartments(Request $request)
    {
        try {
            ApiHrmsSyncJob::dispatch('syncDepartments');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Department sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Department sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all departments.',
            'success' => true,
        ]);
    }
    public function syncUsers(Request $request)
    {
        try {
            ApiHrmsSyncJob::dispatch('syncUsers');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch User sync job', ['error' => $e->getMessage()]);
            throw new \Exception("User sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all users.',
            'success' => true,
        ]);
    }
    public function syncAccessibilities(Request $request)
    {
        try {
            ApiHrmsSyncJob::dispatch('syncAccessibilities');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Accessibility sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Accessibility sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all accessibilities.',
            'success' => true,
        ]);
    }

    // INVENTORY
    public function syncAllInventory(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncAll');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Inventory sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Inventory sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all Inventorys.',
            'success' => true,
        ]);
    }
    public function syncSuppliers(Request $request)
    {
        try {
            ApiInventorySyncJob::dispatch('syncSuppliers');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Inventory sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Inventory sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all suppliers.',
            'success' => true,
        ]);
    }
    // PROJECT MONITORING
    public function syncAllProjectMonitoring(Request $request)
    {
        DB::transaction(function () {
            $projectService = new ProjectMonitoringSecretKeyService();
            if (!$projectService->syncAll()) {
                throw new \Exception("Project monitoring sync failed.");
            }
        });
        return response()->json([
            'message' => 'Successfully synced with Project Monitoring api service.',
            'success' => true,
        ]);
    }
    public function syncProjects(Request $request)
    {
        try {
            ApiProjectsSyncJob::dispatch('syncProjects');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch Project sync job', ['error' => $e->getMessage()]);
            throw new \Exception("Project sync failed: " . $e->getMessage());
        }
        return response()->json([
            'message' => 'Successfully synced all projects.',
            'success' => true,
        ]);
    }
}
