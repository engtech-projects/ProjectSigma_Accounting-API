<?php

namespace App\Services\ApiServices;

use App\Models\Stakeholders\Project;
use Illuminate\Support\Facades\Http;
use Log;

class ProjectMonitoringService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
        $this->apiUrl = config('services.url.project_api');
    }

    public function syncAll()
    {
        $syncProject = $this->syncProjects();

        return $syncProject;
    }

    public function syncProjects()
    {
        $projects = $this->getAllProjects();
        $projects = collect($projects)->map(function ($project) {
            return [
                'id' => $project['id'],
                'project_monitoring_id' => $project['id'],
                'project_code' => $project['code'],
                'status' => $project['status'],
            ];
        })->toArray();
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
                    'stakeholdable_type' => Project::class,
                    'stakeholdable_id' => $project['id'],
                ],
                [
                    'name' => $project['project_code'],
                ]
            );
        }

        return true;
    }

    public function getAllProjects()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                'stage' => 'awarded',
                'status' => 'ongoing',
                'paginate' => false,
                'sort' => 'asc',
            ])
            ->acceptJson()
            ->get($this->apiUrl.'/api/projects');
        if (! $response->successful()) {
            return [];
        }
        return $response->json();
    }
}
