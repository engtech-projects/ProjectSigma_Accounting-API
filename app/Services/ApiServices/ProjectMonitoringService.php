<?php

namespace App\Services\ApiServices;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProjectMonitoringService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct()
    {
        $this->apiUrl = config('services.url.project_api');
        $this->authToken = config('services.sigma.secret_key');
        if (empty($this->authToken)) {
            throw new \InvalidArgumentException('SECRET KEY is not configured');
        }
        if (empty($this->apiUrl)) {
            throw new \InvalidArgumentException('Project API URL is not configured');
        }
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
                'source_id' => $project['id'],
                'name' => $project['code'],
            ];
        });
        $projects_stakeholder = collect($projects)->map(function ($project) {
            return [
                'stakeholdable_id' => $project['id'],
                'stakeholdable_type' => Project::class,
                'name' => $project['name'],
            ];
        });
        DB::transaction(function () use ($projects, $projects_stakeholder) {
            Project::upsert($projects->toArray(), ['source_id'], ['name']);
            StakeHolder::upsert(
                $projects_stakeholder->toArray(),
                [
                    'stakeholdable_id',
                    'stakeholdable_type',
                ],
                ['name']
            );
        });

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
