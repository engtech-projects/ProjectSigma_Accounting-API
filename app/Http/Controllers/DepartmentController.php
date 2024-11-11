<?php

namespace App\Http\Controllers;

use App\Services\DepartmentServices;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function department()
    {
        $hrms = DepartmentServices::syncDepartment(auth()->user()->token);
        if($hrms['success']){
            return $hrms['data'];
            // return response()->json(['message' => 'Employee synced successfully']);
        }else{
            return response()->json(['message' => 'Employee sync failed']);
        }
    }
}
