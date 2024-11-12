<?php

namespace App\Http\Controllers;
use App\Services\HrmsServices;
use Illuminate\Http\JsonResponse;

class HrmsController extends Controller
{

    public function syncAll()
    {
        try{
            HrmsServices::syncEmployee(auth()->user()->token);
            HrmsServices::syncDepartment(auth()->user()->token);
            return new JsonResponse([
                'success' => true,
                'message' => 'Employee and Department Successfully Retrieved.',
            ], 200);
        }catch(\Exception $e){
            return new JsonResponse([
                'success' => false,
                'message' => 'Employee and Department sync failed',
            ], 500);
        }
    }
    public function syncEmployee()
    {
        $hrms = HrmsServices::syncEmployee(auth()->user()->token);
        if( $hrms ){
            return new JsonResponse([
                'success' => true,
                'message' => 'Employee Successfully Retrieved.',
            ], 200);
        }else{
            return new JsonResponse([
                'success' => false,
                'message' => 'Employee sync failed',
            ], 500);
        }
    }
    public function syncDepartment()
    {
        $department = HrmsServices::syncDepartment(auth()->user()->token);
        if( $department ){
            return new JsonResponse([
                'success' => true,
                'message' => 'Department Successfully Retrieved.',
            ], 200);
        }else{
            return new JsonResponse([
                'success' => false,
                'message' => 'Department sync failed',
            ], 500);
        }
    }

}
