<?php

namespace App\Http\Controllers;

use App\Enums\IsActiveType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountEditRequest;
use App\Http\Requests\AccountFilterRequest;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountCollection;
use App\Services\AccountService;
use DB;
use Illuminate\Http\Request;
use App\Http\Resources\AccountsResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;


class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccountFilterRequest $request)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Accounts Successfully Retrieved.',
                'data' =>  AccountCollection::collection(AccountService::getPaginated($request->validated()))->response()->getData(true),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Accounts Failed to Retrieve.',
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
    public function store(AccountRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $validatedData['is_active'] = IsActiveType::TRUE->value;
            $account = Account::create($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Successfully Created.',
                'data' => new AccountsResource($account),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Failed to Create.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $account = Account::with(['accountType'])->findOrFail($id);
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Successfully Created.',
                'data' => AccountsResource::collection($account)->response()->getData(true),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Failed to Create.',
                'data' => null,
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
    public function update(AccountEditRequest $request, string $id)
    {
        DB::beginTransaction();
        $validatedData = $request->validated();
    try {
        $account = Account::findOrFail($id);
        $account->update($validatedData);
        return new JsonResponse([
            'success' => true,
            'message' => 'Account Successfully Updated.',
            'data' => new AccountsResource($account),
        ], 200);
    } catch (\Exception $e) {
        return new JsonResponse([
            'success' => false,
            'message' => 'Account Failed to Update.',
            'data' => null,
        ], 500);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $account = Account::with(['journalEntryDetails'])->findOrFail($id);
            if ($account->journalEntryDetails()->exists()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Account is used in journal entry details and cannot be deleted.',
                    'data' => null,
                ], 400);
            }
            $account->delete();
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Successfully Deleted.',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Failed to Delete.',
                'data' => null,
            ], 500);
        }
    }
}
