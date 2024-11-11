<?php

namespace App\Services;

use App\Models\Department;
use Http;

class DepartmentServices
{
    public static function syncDepartment($token)
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.url.hrms_api_url')."/api/department/list");
        if (!$response->successful()) {
            return false;
        }
        $departments = $response->json()['data'];
        foreach ($departments as $department) {
            Department::updateOrCreate(
                [
                    'source_id' => $department['id'],
                ],
            );
        }
        return true;
    }
}

