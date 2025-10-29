<?php

namespace App\Http\Controllers;

use App\Services\ApiServices\HrmsService;
use App\Services\ApiServices\InventoryService;
use App\Services\ApiServices\ProjectMonitoringService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class APiSyncController extends Controller
{
    public function syncAll(Request $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $hrmsService = new HrmsService($authToken);
            $inventoryService = new ProjectMonitoringService($authToken);
            if (! $hrmsService->syncAll() || ! $inventoryService->syncAll()) {
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
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $hrmsService = new HrmsService($authToken);
            if (! $hrmsService->syncEmployees()) {
                throw new \Exception('HRMS sync failed.');
            }
        });

        return response()->json([
            'message' => 'Successfully synced all employees.',
            'success' => true,
        ]);
    }

    public function syncDepartments(Request $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $hrmsService = new HrmsService($authToken);
            if (! $hrmsService->syncDepartments()) {
                throw new \Exception('HRMS sync failed.');
            }
        });

        return response()->json([
            'message' => 'Successfully synced all departments.',
            'success' => true,
        ]);
    }

    public function syncUsers(Request $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $hrmsService = new HrmsService($authToken);
            if (! $hrmsService->syncUsers()) {
                throw new \Exception('HRMS sync failed.');
            }
        });

        return response()->json([
            'message' => 'Successfully synced all Users.',
            'success' => true,
        ]);
    }

    public function syncProjects(Request $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $projectService = new ProjectMonitoringService($authToken);
            if (! $projectService->syncProjects()) {
                throw new \Exception('Project monitoring sync failed.');
            }
        });

        return response()->json([
            'message' => 'Successfully synced all projects.',
            'success' => true,
        ]);
    }

    public function syncSuppliers(Request $request)
    {
        $authToken = $request->bearerToken();
        DB::transaction(function () use ($authToken) {
            $inventoryService = new InventoryService($authToken);
            if (! $inventoryService->syncSuppliers()) {
                throw new \Exception('Inventory sync failed.');
            }
        });

        return response()->json([
            'message' => 'Successfully synced all suppliers.',
            'success' => true,
        ]);
    }
}
