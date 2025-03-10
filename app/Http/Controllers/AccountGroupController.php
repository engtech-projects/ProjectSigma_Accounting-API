<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountGroupRequest;
use App\Http\Resources\AccountingCollections\AccountGroupCollection;
use App\Models\AccountGroup;
use App\Services\AccountGroupService;
use DB;
use Illuminate\Http\JsonResponse;

class AccountGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccountGroupRequest $request)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Groups Successfully Retrieved.',
                'data' => AccountGroupCollection::collection(AccountGroupService::getPaginated($request->validated()))->response()->getData(true),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Groups Failed to Retrieve.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountGroupRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $accountGroup = AccountGroup::create($validatedData);

            return new JsonResponse([
                'success' => true,
                'message' => 'Account Group Successfully Created.',
                'data' => new AccountGroupCollection($accountGroup),
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Group Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountGroup $accountGroup)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Group Successfully Retrieved.',
                'data' => new AccountGroupCollection($accountGroup),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Group Failed to Retrieve.',
            ], 500);
        }
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
    public function update(AccountGroupRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $accountGroup = AccountGroup::find($id);
            if (! $accountGroup) {
                throw new \Exception('Account Group not found.');
            }
            $accountGroup->update($validatedData);
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Account Group Successfully Updated.',
                'data' => new AccountGroupCollection($accountGroup),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Account Group Failed to Update.',
                'error' => $e->getMessage(),
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
            $accountGroup = AccountGroup::find($id);
            if (! $accountGroup) {
                throw new \Exception('Account Group not found.');
            }
            $accountExist = AccountGroupService::isExistAccountGroupAccount($id);
            if ($accountExist) {
                throw new \Exception('Account Group has account(s).');
            }
            $accountGroup->delete();
            DB::commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Account Group Successfully Deleted.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'success' => false,
                'message' => 'Account Group Failed to Delete.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
