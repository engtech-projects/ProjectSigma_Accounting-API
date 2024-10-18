<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Collections\AccountGroupCollection;
use App\Http\Resources\AccountGroupResource;
use App\Models\AccountGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccountGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountGroups = AccountGroup::all();
		return new AccountGroupCollection($accountGroups);
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
    public function show(AccountGroup $accountGroup)
    {
    	return response()->json(new AccountGroupResource($accountGroup), 201);
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
