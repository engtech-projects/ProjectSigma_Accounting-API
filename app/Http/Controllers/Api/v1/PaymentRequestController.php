<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest\PaymentRequestForm;
use App\Models\PaymentRequest;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'test';
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
    public function store(PaymentRequestForm $request)
    {
		$paymentRequest = PaymentRequest::create($request->validated());

		$details = $request->details;

		$paymentRequest->details()->createMany($request->details);

		return response()->json(['Payment Request' => $paymentRequest], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
