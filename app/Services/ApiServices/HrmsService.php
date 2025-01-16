<?php

namespace App\Services\ApiServices;

use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\User;
use Http;

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
        collect($employees)->map(function ($employee) {
            return [
                'id' => $employee['id'],
                'source_id' => $employee['id'],
                'name' => $employee['fullname_first'],
            ];
        });
        foreach ($employees as $employee) {
            $employee_model = Employee::updateOrCreate(
                [
                    'id' => $employee['id'],
                    'source_id' => $employee['id'],
                ],
                [
                    'name' => $employee['fullname_first'],
                ]
            );
            $employee_model->stakeholder()->updateOrCreate(
                [
                    'stakeholdable_type' => Employee::class,
                    'stakeholdable_id' => $employee['id'],
                ],
                [
                    'name' => $employee['fullname_first'],
                ]
            );
        }

        return true;
    }

    public function syncDepartments()
    {
        $departments = $this->getAllDepartment();
        collect($departments)->map(function ($department) {
            return [
                'id' => $department['id'],
                'source_id' => $department['id'],
                'name' => $department['department_name'],
            ];
        });
        foreach ($departments as $department) {
            $department_model = Department::updateOrCreate(
                [
                    'id' => $department['id'],
                    'source_id' => $department['id'],
                ],
                [
                    'name' => $department['department_name'],
                ]
            );
            $department_model->stakeholder()->updateOrCreate(
                [
                    'stakeholdable_type' => Department::class,
                    'stakeholdable_id' => $department['id'],
                ],
                [
                    'name' => $department['department_name'],
                ]
            );
        }

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
            $user_model->stakeholder()->updateOrCreate(
                [
                    'stakeholdable_type' => User::class,
                    'stakeholdable_id' => $user['id'],
                ],
                [
                    'name' => $user['employee']['fullname_first'],
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
