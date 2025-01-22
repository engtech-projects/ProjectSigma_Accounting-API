<?php

namespace App\Services\ApiServices;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\User;
use DB;
use Http;
use Log;

class HrmsService
{
    protected $apiUrl;

    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
        $this->apiUrl = config('services.url.hrms_api');
    }

    public function syncAll()
    {
        $syncData = [
            'employees' => $this->syncEmployees(),
            'departments' => $this->syncDepartments(),
        ];

        return $syncData;
    }

    public function syncEmployees()
    {
        $employees = $this->getAllEmployees();

        $employees = collect(value: $employees)->map(function ($employee) {
            return [
                'id' => $employee['id'],
                'source_id' => $employee['id'],
                'name' => $employee['fullname_first'],
            ];
        });
        $employee_stakeholder = collect(value: $employees)->map(function ($employee) {
            return [
                'name' => $employee['name'],
                'stakeholdable_id' => $employee['id'],
                'stakeholdable_type' => Employee::class,
            ];
        });

        DB::transaction(function ()use ($employees, $employee_stakeholder) {
            Employee::upsert($employees->toArray(), ['source_id'], ['name']);
            StakeHolder::upsert(
                $employee_stakeholder->toArray(),
                [
                    'stakeholdable_id',
                    'stakeholdable_type'
                ],
                ['name']
            );
        });

        return true;
    }

    public function syncDepartments()
    {
        $departments = $this->getAllDepartment();
        $departments = collect($departments)->map(function ($department) {
            return [
                'id' => $department['id'],
                'source_id' => $department['id'],
                'name' => $department['department_name'],
            ];
        });
        $department_stakeholder = collect($departments)->map(function ($department) {
            return [
                'stakeholdable_id' => $department['id'],
                'stakeholdable_type' => Department::class,
                'name' => $department['name'],
            ];
        });
        DB::transaction(function ()use ($departments, $department_stakeholder) {
            Department::upsert($departments->toArray(), ['source_id'], ['name']);
            StakeHolder::upsert(
                $department_stakeholder->toArray(),
                [
                    'stakeholdable_id',
                    'stakeholdable_type'
                ],
                ['name']
            );
        });

        return true;
    }

    public function syncUsers()
    {
        $users = $this->getAllUsers();
        collect($users)->map(function ($user) {
            return [
                'id' => $user['id'],
                'source_id' => $user['id'],
                'name' => $user['employee']['fullname_first'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'password' => '-',
                'remember_token' => null,
            ];
        });
        foreach ($users as $user) {
            $user_model = User::updateOrCreate(
                [
                    'id' => $user['id'],
                    'source_id' => $user['id'],
                ],
                [
                    'name' => $user['employee']['fullname_first'],
                    'email' => $user['email'],
                    'email_verified_at' => $user['email_verified_at'],
                    'password' => '-',
                    'remember_token' => null,
                ]
            );
        }

        return true;
    }

    public function getAllEmployees()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get($this->apiUrl.'/api/employee/list');


        Log::info($response);
        if (! $response->successful()) {
            return [];
        }

        return $response->json()['data'];
    }

    public function getAllUsers()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get($this->apiUrl.'/api/employee/users-list');
        if (! $response->successful()) {
            return [];
        }

        return $response->json()['data'];
    }

    public function getAllDepartment()
    {
        $response = Http::withToken($this->authToken)
            ->acceptJson()
            ->get($this->apiUrl.'/api/department/list/v2');
        if (! $response->successful()) {
            return [];
        }

        return $response->json()['data'];
    }
}
