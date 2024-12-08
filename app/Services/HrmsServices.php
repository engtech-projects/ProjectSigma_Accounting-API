<?php

namespace App\Services;

use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Project;
use App\Models\User;
use DB;
use Http;

class HrmsServices
{
    public static function syncEmployee($token)
    {
        DB::beginTransaction();
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url').'/api/employee/list');
            if (! $response->successful()) {
                return false;
            }
            $employees = $response->json()['data'];
            $totalEmployeeCount = Employee::count();
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
            DB::commit();
            $total_inserted = Employee::count() - $totalEmployeeCount;

            return $total_inserted;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public static function syncProject($token)
    {
        DB::beginTransaction();
        try {
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
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public static function syncUsers($token)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.hrms_api_url').'/api/employee/users-list');
        if (! $response->successful()) {
            return false;
        }
        $users = $response->json()['data'];
        $totalUserCount = User::count();
        DB::table('users')->upsert(
            collect($users)->map(fn ($user) => [
                'id' => $user['id'],
                'source_id' => $user['id'],
                'name' => $user['employee']['fullname_first'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'password' => '-',
                'remember_token' => null,
            ])->toArray(),
            ['id', 'source_id'],
            ['name', 'email', 'email_verified_at', 'password', 'remember_token']
        );
        $total_inserted = User::count() - $totalUserCount;

        return $total_inserted;
    }

    public static function syncDepartment($token)
    {
        DB::beginTransaction();
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url').'/api/department/list/v2');
            if (! $response->successful()) {
                return false;
            }
            $departments = $response->json()['data'];
            $totalDepartmentCount = Department::count();
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
            DB::commit();
            $total_inserted = Department::count() - $totalDepartmentCount;

            return $total_inserted;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public static function setNotification($token, $userid, $notificationData)
    {
        if (gettype($notificationData) == 'array') {
            $notificationData = json_encode($notificationData);
        }
        $response = Http::withToken(token: $token)
            ->acceptJson()
            ->withBody($notificationData)
            ->post(config('services.url.hrms_api_url')."/api/notifications/services-notify/{$userid}");
        if (! $response->successful()) {
            return false;
        }
    }

    public static function formatApprovals($token, $approvals)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->withQueryParameters($approvals)
            ->get(config('services.url.hrms_api_url').'/api/services/format-approvals');
        if (! $response->successful()) {
            return $approvals;
        }

        return $response->json()['data'];
    }

    public static function getEmployeeDetails($token, $user_ids)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.hrms_api_url').'/api/services/user-employees', [
                'user_ids' => $user_ids,
            ]);

        if (! $response->successful()) {
            return false;
        }

        return $response->json('data');
    }
}
