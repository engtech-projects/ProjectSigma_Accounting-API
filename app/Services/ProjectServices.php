<?php

namespace App\Services;

use App\Models\Stakeholders\Project;
use DB;
use Http;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProjectServices
{
    public static function syncAll()
    {
        try{
            self::syncProject(auth()->user()->token);
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
    public static function syncProject($token)
    {
        DB::beginTransaction();
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.project_api_url')."/api/projects");
            if (!$response->successful()) {
                return false;
            }
            $projects = $response->json()['data'];
            $total_inserted = 0;
            foreach ($projects as $project) {
                $project_model = Project::updateOrCreate(
                    [
                        'id' => $project['id'],
                        'source_id' => $project['id'],
                    ],
                    [
                        'name' => $project['project_code'],
                        'source_id' => $project['id'],
                    ],
                );
                if ($project_model->stakeholder()->updateOrCreate(
                    [
                        'source_id' => $project['id'],
                        'stakeholdable_type' => Project::class,
                        'stakeholdable_id' => $project['id'],
                    ],
                    [
                        'name' => $project['project_code'],
                    ]
                )) {
                    $total_inserted++;
                }
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'Project Successfully Retrieved.',
                'total_inserted' => $total_inserted,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

}

