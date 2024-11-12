<?php

namespace App\Http\Controllers;
use App\Services\HrmsServices;
use App\Services\ProjectServices;

class HrmsController extends Controller
{

    public function syncAll()
    {
        try{
            HrmsServices::syncEmployee(auth()->user()->token);
            HrmsServices::syncDepartment(auth()->user()->token);
            return response()->json(['message' => 'Employee and Department synced successfully']);
        }catch(\Exception $e){
            return response()->json(['message' => 'Employee and Department sync failed']);
        }
    }
    public function syncEmployee()
    {
        $hrms = HrmsServices::syncEmployee(auth()->user()->token);
        if( $hrms ){
            return response()->json(['message' => 'Employee synced successfully']);
        }else{
            return response()->json(['message' => 'Employee sync failed']);
        }
    }
    public function syncDepartment()
    {
        $department = HrmsServices::syncDepartment(auth()->user()->token);
        if( $department ){
            return response()->json(['message' => 'Department synced successfully']);
        }else{
            return response()->json(['message' => 'Department sync failed']);
        }
    }

}
