<?php

namespace App\Http\Controllers;

use App\Models\Subsidiary;
use App\Http\Requests\StoreSubsidiaryRequest;
use App\Http\Requests\UpdateSubsidiaryRequest;

class SubsidiaryController extends Controller
{
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
    public function store(StoreSubsidiaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subsidiary $subsidiary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubsidiaryRequest $request, Subsidiary $subsidiary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subsidiary $subsidiary)
    {
        //
    }
}
