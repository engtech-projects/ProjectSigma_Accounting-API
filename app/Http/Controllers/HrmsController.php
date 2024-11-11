<?php

namespace App\Http\Controllers;

use App\Services\HrmsServices;
use App\Services\ProjectServices;

class HrmsController extends Controller
{

    public function employee()
    {
        $hrms = HrmsServices::syncEmployee(auth()->user()->token);
        if($hrms['success']){
            return $hrms['data'];
            // return response()->json(['message' => 'Employee synced successfully']);
        }else{
            return response()->json(['message' => 'Employee sync failed']);
        }
    }
    public function project()
    {
        return ProjectServices::syncProject();
    }
}
