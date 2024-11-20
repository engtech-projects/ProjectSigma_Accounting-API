<?php

namespace App\Http\Controllers;

use App\Enums\StakeHolderType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StakeholderEditRequest;
use App\Http\Requests\StakeholderFilterRequest;
use App\Http\Requests\StakeholderRequest;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Project;
use App\Models\Stakeholders\Supplier;
use App\Services\StakeHolderService;
use DB;
use Illuminate\Http\Request;
use App\Http\Resources\StakeholderResource;
use App\Models\StakeHolder;
use Symfony\Component\HttpFoundation\JsonResponse;

class StakeHolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StakeholderFilterRequest $request)
    {
        $validatedData = $request->validated();
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Types Successfully Retrieved.',
                'data' =>  StakeholderResource::collection(StakeHolderService::getPaginated($validatedData))->response()->getData(true),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Types Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StakeholderRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
            $validatedData['stakeholdable_id'] = match ($validatedData['stakeholder_type']) {
                StakeHolderType::SUPPLIER->value => StakeHolderService::createSupplier($validatedData),
                StakeHolderType::EMPLOYEE->value => StakeHolderService::createEmployee($validatedData),
                StakeHolderType::PROJECTS->value => StakeHolderService::createProject($validatedData),
                StakeHolderType::DEPARTMENT->value => StakeHolderService::createDepartment($validatedData),
            };
            $validatedData['stakeholdable_type'] = match ($validatedData['stakeholdable_type']) {
                StakeHolderType::SUPPLIER->value  => Supplier::class,
                StakeHolderType::EMPLOYEE->value  => Employee::class,
                StakeHolderType::PROJECTS->value  => Project::class,
                StakeHolderType::DEPARTMENT->value  => Department::class,
            };
            StakeHolder::create($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Types Successfully Retrieved.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Types Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StakeHolder $stakeholder)
    {
        return response()->json(new StakeholderResource($stakeholder));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StakeholderEditRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validateData = $request->validated();
            $stakeholder = StakeHolder::where('stakeholdable_id', $id)->first();

            match ($stakeholder->stakeholderable_type) {
                Supplier::class => Supplier::where('source_id',$validateData['id'])->update(['name' => $validateData['name']]),
                Employee::class => Employee::where('source_id',$validateData['id'])->update(['name' => $validateData['name']]),
                Project::class => Project::where('source_id',$validateData['id'])->update(['name' => $validateData['name']]),
                Department::class => Department::where('source_id',$validateData['id'])->update(['name' => $validateData['name']]),
            };
            $stakeholder->delete();
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Types Successfully Retrieved.',
                'data' =>  [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Types Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $stakeholder = StakeHolder::where('stakeholdable_id', $id)->first();
            match ($stakeholder->stakeholderable_type) {
                Supplier::class => Supplier::find($id)->delete(),
                Employee::class => Employee::find($id)->delete(),
                Project::class => Project::find($id)->delete(),
                Department::class => Department::find($id)->delete(),
            };
            $stakeholder->delete();
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Types Successfully Retrieved.',
                'data' =>  [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Types Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }
}
