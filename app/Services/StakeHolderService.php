<?php

namespace App\Services;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Payee;
use App\Models\Stakeholders\Project;
use App\Models\Stakeholders\Supplier;

class StakeHolderService
{
    public static function searchStakeHolders(array $validatedData)
    {
        return StakeHolder::where('name', 'like', '%'. strtolower($validatedData['key']) .'%')
            ->where('stakeholdable_type', "App\Models\Stakeholders\\" . ucfirst($validatedData['type']))
            ->paginate(config('app.pagination_limit'));
    }

    public static function getPaginated(array $filters = [])
    {
        $query = StakeHolder::query();
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        return $query->paginate(config('services.pagination.limit'));
    }
    public static function createEmployee ($data)
    {
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $id = $lastEmployee ? $lastEmployee->id + 1 : 1;
        $employee = Employee::create([
            'id' => $id,
            'name' => $data['name'],
            'source_id' => $id,
        ]);
        return $id;
    }
    public static function createProject ($data)
    {
        $lastProject = Project::orderBy('id', 'desc')->first();
        $id = $lastProject ? $lastProject->id + 1 : 1;
        $project = Project::create([
            'id' => $id,
            'name' => $data['name'],
            'source_id' => $id,
        ]);
        return $id;
    }
    public static function createPayee ($data)
    {
        $lastProject = Payee::orderBy('id', 'desc')->first();
        $id = $lastProject ? $lastProject->id + 1 : 1;
        $project = Project::create([
            'id' => $id,
            'name' => $data['name'],
            'source_id' => $id,
        ]);
        return $id;
    }
    public static function createDepartment ($data)
    {
        $lastDepartment = Department::orderBy('id', 'desc')->first();
        $id = $lastDepartment ? $lastDepartment->id + 1 : 1;
        $department = Department::create([
            'id' => $id,
            'name' => $data['name'],
            'source_id' => $id,
        ]);
        return $id;
    }
    public static function createSupplier ($data)
    {
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $id = $lastSupplier ? $lastSupplier->id + 1 : 1;
        $supplier = Supplier::create([
            'id' => $id,
            'name' => $data['name'],
            'source_id' => $id,
        ]);
        return $id;
    }
}
