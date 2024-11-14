<?php

namespace App\Services;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Project;
use DB;
use Http;

class HrmsServices
{
    public static function syncAll()
    {
        try{
            self::syncEmployee(auth()->user()->token);
            self::syncDepartment(auth()->user()->token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function syncEmployee($token)
    {
        DB::beginTransaction();
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url')."/api/employee/list");
            if (!$response->successful()) {
                return false;
            }
            $employees = $response->json()['data'];
            $total_inserted = 0;
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
                if ($employee_model->stakeholder()->updateOrCreate(
                    [
                        'stakeholdable_type' => Employee::class,
                        'stakeholdable_id' => $employee['id'],
                    ],
                    [
                        'name' => $employee['fullname_first'],
                    ]
                )) {
                    $total_inserted++;
                }
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'Employee Successfully Retrieved.',
                'total_inserted' => $total_inserted,
            ];
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
    public static function syncDepartment($token)
    {
        DB::beginTransaction();
        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get(config('services.url.hrms_api_url')."/api/department/list/v2");
            if (!$response->successful()) {
                return false;
            }
            $departments = $response->json()['data'];
            $total_inserted = 0;
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
                if ($department_model->stakeholder()->updateOrCreate(
                    [
                        'stakeholdable_type' => Department::class,
                        'stakeholdable_id' => $department['id'],
                    ],
                    [
                        'name' => $department['department_name'],
                    ]
                )) {
                    $total_inserted++;
                }
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'Department Successfully Retrieved.',
                'total_inserted' => $total_inserted,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public static function setNotification($token, $userid, $notificationData)
    {
        if(gettype($notificationData) == "array") {
            $notificationData = json_encode($notificationData);
        }
        $response = Http::withToken(token: $token)
            ->acceptJson()
            ->withBody($notificationData)
            ->post(config('services.url.hrms_api_url')."/api/notifications/services-notify/{$userid}");
        if (!$response->successful()) {
            return false;
        }
    }

    public static function formatApprovals($token, $approvals)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->withQueryParameters($approvals)
            ->get(config('services.url.hrms_api_url')."/api/services/format-approvals");
        if (!$response->successful()) {
            return $approvals;
        }
        return $response->json()["data"];
    }
    public static function getEmployeeDetails($token, $user_ids)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.hrms_api_url') . '/api/services/user-employees', [
                'user_ids' => $user_ids
            ]);

        if (!$response->successful()) {
            return false;
        }

        return $response->json("data");
    }
}
