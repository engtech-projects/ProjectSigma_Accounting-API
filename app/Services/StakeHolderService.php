<?php

namespace App\Services;

use App\Models\StakeHolder;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
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
        $employee = Employee::create([
            'name' => $data['name'],
            'source_id' => Employee::latest()->first()->id + 1,
        ]);
        return $employee->id;
    }
    public static function createProject ($data)
    {
        $project = Project::create([
            'name' => $data['name'],
            'source_id' => Project::latest()->first()->id + 1,
        ]);
        return $project->id;
    }
    public static function createDepartment ($data)
    {
        $department = Department::create([
            'name' => $data['name'],
            'source_id' => Department::latest()->first()->id + 1,
        ]);
        return $department->id;
    }
    public static function createSupplier ($data)
    {
        $supplier = Supplier::create([
            'name' => $data['name'],
            'source_id' => Supplier::latest()->first()->id + 1,
        ]);
        return $supplier->id;
    }
}
