<?php

namespace App\Services;

use App\Models\Stakeholders\Project;
use DB;
use Http;

class ProjectServices
{
    public static function syncProject($token)
    {
        DB::beginTransaction();
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.project_api_url').'/api/projects');
        if (! $response->successful()) {
            return false;
        }
        $projects = $response->json()['data'];
        $totalProjectCount = Project::count();
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
            $project_model->stakeholder()->updateOrCreate(
                [
                    'source_id' => $project['id'],
                    'stakeholdable_type' => Project::class,
                    'stakeholdable_id' => $project['id'],
                ],
                [
                    'name' => $project['project_code'],
                ]
            );
        }
        DB::commit();
        $total_inserted = Project::count() - $totalProjectCount;

        return $total_inserted;
    }
}
