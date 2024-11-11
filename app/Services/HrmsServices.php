<?php

namespace App\Services;
use App\Models\Employee;
use App\Models\StakeHolder;
use Http;

class HrmsServices
{

    public static function syncEmployee($token)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.hrms_api_url')."/api/employee/list");
        if (!$response->successful()) {
            return false;
        }
        $employees = $response->json()['data'];
        $success = true;
        $data = [];

        foreach ($employees as $employee) {
            StakeHolder::updateOrCreate(
                [
                    'id' => $employee['id'],
                    'source_id' => $employee['id'],
                    'type' => 'employee'
                ],
                [
                    'name' => $employee['fullname_first'],
                ]
            );
            Employee::updateOrCreate(
                [
                    'id' => $employee['id'],
                    'source_id' => $employee['id'],
                ],
                [
                    'name' => $employee['fullname_first'],
                ]
            );
        }

        return [
            'success' => $success,
            'data' => $data
        ];
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
