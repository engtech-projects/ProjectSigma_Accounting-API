<?php

namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use App\Services\ProjectServices;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function syncAll()
    {
        try{
            ProjectServices::syncProject(auth()->user()->token);
            return new JsonResponse([
                'success' => true,
                'message' => 'Project Successfully Retrieved.',
            ], 200);
        }catch(\Exception $e){
            return new JsonResponse([
                'success' => false,
                'message' => 'Project sync failed',
            ], 500);
        }
    }
    public function syncProject()
    {
        $project = ProjectServices::syncProject(auth()->user()->token);
        if( $project ){
            return new JsonResponse([
                'success' => true,
                'message' => 'Project Successfully Retrieved.',
            ], 200);
        }else{
            return new JsonResponse([
                'success' => false,
                'message' => 'Project sync failed',
            ], 500);
        }
    }
}
