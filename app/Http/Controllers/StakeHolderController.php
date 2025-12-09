<?php

namespace App\Http\Controllers;

use App\Enums\StakeHolderType;
use App\Http\Requests\Stakeholder\StakeholderRequestFilter;
use App\Http\Requests\Stakeholder\StakeholderRequestStore;
use App\Http\Requests\Stakeholder\StakeholderRequestUpdate;
use App\Http\Resources\AccountingCollections\StakeholderCollection;
use App\Models\StakeHolder;
use App\Models\Stakeholders\Department;
use App\Models\Stakeholders\Employee;
use App\Models\Stakeholders\Payee;
use App\Models\Stakeholders\Project;
use App\Models\Stakeholders\Supplier;
use App\Services\StakeHolderService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class StakeHolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StakeholderRequestFilter $request)
    {
        $validatedData = $request->validated();
        $query = StakeHolder::query();
        if (!empty($validatedData['key'])) {
            $query->where('name', 'like', '%'.strtolower($validatedData['key']).'%');
        }
        if (!empty($validatedData['type'])) {
            $modelClass = StakeHolderType::from($validatedData['type'])->getModelClass();
            $query->where('stakeholdable_type', $modelClass);
        }
        $stakeholder = $query->paginate(config('app.pagination.limit'));
        return StakeholderCollection::collection($stakeholder)->additional([
            'success' => true,
            'message' => 'Stakeholders Successfully Retrieved.',
        ]);
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
    public function store(StakeholderRequestStore $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
            $validatedData['stakeholdable_id'] = StakeHolderService::createPayee($validatedData);
            $validatedData['stakeholdable_type'] = Payee::class;
            StakeHolder::create($validatedData);
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Payee Successfully Save.',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Payee Failed to Save.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StakeHolder $stakeholder)
    {
        return response()->json(new StakeholderCollection($stakeholder));
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
    public function update(StakeholderRequestUpdate $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validateData = $request->validated();
            $stakeholder = StakeHolder::where('stakeholdable_id', $id)->first();

            match ($stakeholder->stakeholderable_type) {
                Supplier::class => Supplier::where('source_id', $validateData['id'])->update(['name' => $validateData['name']]),
                Employee::class => Employee::where('source_id', $validateData['id'])->update(['name' => $validateData['name']]),
                Project::class => Project::where('source_id', $validateData['id'])->update(['name' => $validateData['name']]),
                Department::class => Department::where('source_id', $validateData['id'])->update(['name' => $validateData['name']]),
            };
            $stakeholder->delete();
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Payee Successfully Update.',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Payee Failed to Update.',
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
            Payee::find($id)->delete();
            $stakeholder->delete();
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Payee Successfully Delete.',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Payee Failed to Delete.',
                'data' => null,
            ], 500);
        }
    }
}
