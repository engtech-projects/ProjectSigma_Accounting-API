<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\ProjectServices;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public static function syncAll()
    {
        try {
            $syncProject = ProjectServices::syncProject(auth()->user()->token);

            return new JsonResponse([
                'success' => true,
                'message' => 'Project Successfully Retrieved.',
                'total_inserted' => [
                    'project' => $syncProject,
                ],
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Project sync failed',
            ], 500);
        }
    }

    public function syncProject()
    {
        $syncProject = ProjectServices::syncProject(auth()->user()->token);
        if ($syncProject) {
            return new JsonResponse([
                'success' => true,
                'message' => 'Project Successfully Retrieved.',
                'total_inserted' => $syncProject,
            ], 200);
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => 'Project sync failed',
            ], 500);
        }
    }
}
