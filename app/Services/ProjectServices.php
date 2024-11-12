<?php

namespace App\Services;

use App\Models\Stakeholders\Project;
use Http;


class ProjectServices
{
    public static function syncProject($token)
    {
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.project_api_url')."/api/projects");
            if (!$response->successful()) {
                return false;
            }
            $projects = $response->json()['data'];
            foreach ($projects as $project) {
                $project_model = Project::updateOrCreate(
                    [
                        'id' => $project['id'],
                        'source_id' => $project['id'],
                    ],
                    [
                        'name' => $project['project_code'],
                    ]
                );
                $project_model->stakeholder()->updateOrCreate(
                    [
                        'stakeholdable_type' => Project::class,
                        'stakeholdable_id' => $project['id'],
                    ],
                    [
                        'name' => $project['project_code'],
                    ]
                );
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}

