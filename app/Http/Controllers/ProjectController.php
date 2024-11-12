<?php

namespace App\Http\Controllers;

use App\Services\ProjectServices;

class ProjectController extends Controller
{
    public function syncAll()
    {
        try{
            ProjectServices::syncProject(auth()->user()->token);
            return response()->json(['message' => 'Project synced successfully']);
        }catch(\Exception $e){
            return response()->json(['message' => 'Project sync failed']);
        }
    }
    public function syncProject()
    {
        $project = ProjectServices::syncProject(auth()->user()->token);
        if( $project ){
            return response()->json(['message' => 'Project synced successfully']);
        }else{
            return response()->json(['message' => 'Project sync failed']);
        }
    }
}
