<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountTypeRequest;
use App\Http\Resources\AccountTypeCollection;
use App\Services\AccountTypeService;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\AccountTypeResource;
use App\Models\AccountType;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Types Successfully Retrieved.',
                'data' =>  AccountTypeCollection::collection(AccountTypeService::getPaginated())->response()->getData(true),
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
    public function create(AccountTypeRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountTypeRequest $request)
    {
        DB::beginTransaction();
        try {
            $accountType = AccountType::create($request->validated());
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Type Successfully Created.',
                'data' => new AccountTypeResource($accountType),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Type Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $accountType = AccountType::find($id);
        if (!$accountType) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Type Not Found.',
                'data' => null,
            ], 404);
        }
        return new JsonResponse([
            'success' => true,
            'message' => 'Account Type Successfully Retrieved.',
            'data' => new AccountTypeResource($accountType),
        ], 200);
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
    public function update(AccountTypeRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $accountType = AccountType::find($id);
            $accountType->update($request->validated());
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Type Successfully Updated.',
                'data' => new AccountTypeResource($accountType),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Type Failed to Update.',
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
            $accountType = AccountType::find($id);
            if (!$accountType) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Account Type Not Found.',
                    'data' => null,
                ], 404);
            }

            if ($accountType->accounts()->exists()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Cannot delete account type that is being used by accounts.',
                    'data' => null,
                ], 422);
            }
            $accountType = AccountType::find($id);
            $accountType->delete();
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Type Successfully Deleted.',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Type Failed to Delete.',
                'data' => null,
            ], 500);
        }
    }
}
