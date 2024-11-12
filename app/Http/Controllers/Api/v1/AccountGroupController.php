<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AccountingCollections\AccountGroupCollection;
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
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Groups Successfully Retrieved.',
                'data' => AccountGroup::all(),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Groups Failed to Retrieve.',
            ], 500);
        }
    }
    public function getWithPagination()
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Groups Successfully Retrieved.',
                'data' => AccountGroup::paginate(config('services.pagination.limit')),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Account Groups Failed to Retrieve.',
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
    public function show(AccountGroup $accountGroup)
    {
        try {
            return new JsonResponse([
                'success' => true,
                'message' => 'Account Group Successfully Retrieved.',
                'data' => new AccountGroupResource($accountGroup),
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
