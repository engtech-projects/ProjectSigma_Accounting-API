<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AccountCategory;
use App\Http\Requests\StoreAccountCategoryRequest;
use App\Http\Requests\UpdateAccountCategoryRequest;
use App\Services\Api\V1\AccountService;

class AccountCategoryController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountCategory $accountCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountCategoryRequest $request, AccountCategory $accountCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountCategory $accountCategory)
    {
        //
    }
}
