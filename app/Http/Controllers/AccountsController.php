<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountCollection;
use App\Services\AccountService;
use Illuminate\Http\Request;
use App\Http\Resources\AccountsResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;


class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccountRequest $request)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Accounts Successfully Retrieved.',
                'data' =>  AccountCollection::collection(AccountService::getPaginated($request->validated())),
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return response()->json(new AccountsResource($account));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
