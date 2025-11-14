<?php

namespace App\Services\ApiServices;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Department;
use App\Models\User;

class HrmsService
{
    protected $apiUrl;
    protected $authToken;

    public function __construct()
    {
        $this->apiUrl = config('services.url.hrms_api');
        $this->authToken = config('services.sigma.secret_key');
        if (empty($this->authToken)) {
            throw new \InvalidArgumentException('SECRET KEY is not configured');
        }
        if (empty($this->apiUrl)) {
            throw new \InvalidArgumentException('Projects API URL is not configured');
        }
    }

    public function syncAll()
    {
        $syncEmployees = $this->syncEmployees();
        $syncDepartments = $this->syncDepartments();
        $syncUsers = $this->syncUsers();
        return $syncEmployees && $syncDepartments && $syncUsers;
    }

    public function syncEmployees()
    {
        $employees = $this->getAllEmployees();
        $employees = collect($employees)->map(function ($employee) {
            return [
                'id' => $employee['id'],
                'source_id' => $employee['id'],
                'name' => $employee['first_name'] . ', ' . $employee['middle_name'] . ', ' . $employee['family_name'],
            ];
        });
        Employee::upsert(
            $employees->toArray(),
            ['source_id', 'id'],
            ['name']
        );
        return true;
    }

    public function syncDepartments()
    {
        $departments = $this->getAllDepartments();
        $departments = collect($departments)->map(function ($department) {
            return [
                'id' => $department['id'],
                'source_id' => $department['id'],
                'name' => $department['department_name'],
                'code' => $department['code'],
            ];
        });
        Department::upsert(
            $departments->toArray(),
            [
                'source_id',
                'id',
            ],
            [
                'name',
                'code',
            ]
        );
        return true;
    }

    public function syncUsers()
    {
        $users = $this->getAllUsers();
        $users = collect($users)->map(function ($user) {
            return [
                'id' => $user['id'],
                'source_id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'password' => $user['password'],
                'remember_token' => $user['remember_token'],
            ];
        });
        User::upsert(
            $users->toArray(),
            [
                'id',
                'source_id',
            ],
            [
                'name',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
            ]
        );
        return true;
    }

    public function getAllEmployees()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                'paginate' => false,
                'sort' => 'asc',
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/employee');
        if (!$response->successful()) {
            return [];
        }
        return $response->json("data") ?: [];
    }

    public function getAllDepartments()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                "paginate" => false,
                "sort" => "asc"
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/department');
        if (!$response->successful()) {
            Log::channel("HrmsService")->error('Failed to fetch departments from monitoring API', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return [];
        }
        $data = $response->json();
        if (!isset($data['data']) || !is_array($data['data'])) {
            Log::channel("HrmsService")->warning('Unexpected response format from departments API', ['response' => $data]);
            return [];
        }
        return $data['data'];
    }
       public function getAllUsers()
    {
        $response = Http::withToken($this->authToken)
            ->withUrlParameters([
                "paginate" => false,
                "sort" => "asc"
            ])
            ->acceptJson()
            ->get($this->apiUrl . '/api/sigma/sync-list/user');
        if (!$response->successful()) {
            Log::channel("HrmsService")->error('Failed to fetch users from monitoring API', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return [];
        }
        $data = $response->json();
        if (!isset($data['data']) || !is_array($data['data'])) {
            Log::channel("HrmsService")->warning('Unexpected response format from users API', ['response' => $data]);
            return [];
        }
        return $data['data'];
    }
}
