<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\Controller;
use App\Services\HrmsServices;
use Illuminate\Http\JsonResponse;

class HrmsController extends Controller
{
    public static function syncAll()
    {
        $syncEmployee = HrmsServices::syncEmployee(auth()->user()->token);
        $syncDepartment = HrmsServices::syncDepartment(auth()->user()->token);
        $syncUsers = HrmsServices::syncUsers(auth()->user()->token);

        return new JsonResponse([
            'success' => true,
            'message' => 'Employee and Department Successfully Retrieved.',
            'total_inserted' => [
                'employee' => $syncEmployee,
                'department' => $syncDepartment,
                'users' => $syncUsers,
            ],
        ], 200);
    }

    public function syncEmployee()
    {
        $hrmsEmployee = HrmsServices::syncEmployee(auth()->user()->token);

        return new JsonResponse([
            'success' => true,
            'message' => 'Employee Successfully Retrieved.',
            'total_inserted' => $hrmsEmployee,
        ], 200);
    }

    public function syncDepartment()
    {
        $department = HrmsServices::syncDepartment(auth()->user()->token);

        return new JsonResponse([
            'success' => true,
            'message' => 'Department Successfully Retrieved.',
            'total_inserted' => $department,
        ], 200);
    }

    public function syncUsers()
    {
        $users = HrmsServices::syncUsers(auth()->user()->token);

        return new JsonResponse([
            'success' => true,
            'message' => 'Users Successfully Retrieved.',
            'total_inserted' => $users,
        ], 200);
    }
}
