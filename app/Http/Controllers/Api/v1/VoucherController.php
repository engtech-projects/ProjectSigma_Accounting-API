<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\v1\Store\StoreVoucherRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Voucher;
use App\Http\Resources\VoucherResource;

/**
 * @OA\Info(
 *    title="Accounting Api",
 *    version="0.0.1",
 *    description="API for managing Accounting Vouchers."
 * )
 */
class VoucherController extends Controller
{

		/**
	 *     @OA\Get(
	 *     path="/api/v1/voucher",
	 *     summary="Get all vouchers",
	 *     tags={"Get a collection of vouchers"},
	 * 	   @OA\Parameter(
	 *         name="Authorization",
	 *         in="header",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string",
	 *             default="Bearer {token}"
	 *         ),
	 *         description="Authorization token"
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Voucher Collection",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="voucher_no", type="string", example="DV-202402-00001"),
	 * 		   	   @OA\Property(property="payee", type="string", example="Maybank"),
	 *             @OA\Property(property="particulars", type="text", example="FORMATTED BY: PAYMENT FOR [*MODE OF PAYROLL], [*MODE OF PAYROLL RELEASE] for the Period Covered: [*DATE OF PAYROLL PERIOD COVERED]"),
	 *             @OA\Property(property="net_amount", type="integer", example="5000"),
	 * 			   @OA\Property(property="date_encoded", type="date:Y-m-d", example="2024-10-11"),
	 * 	           @OA\Property(property="voucher_date", type="date:Y-m-d", example="2024-10-11"),
	 * 			   @OA\Property(property="status", type="string", example="pending")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="unauthorized"
	 *     )
	* 
	* )
	*/
    public function index()
    {
		return VoucherResource::collection(Voucher::all());
    }

	/**
	 *     @OA\Post(
	 *     path="/api/v1/voucher",
	 *     summary="Create a new voucher",
	 *     tags={"Create a voucher"},
	 * 	   @OA\Parameter(
	 *         name="Authorization",
	 *         in="header",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string",
	 *             default="Bearer {token}"
	 *         ),
	 *         description="Authorization token"
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             @OA\Property(property="voucher_no", type="string", example="DV-202402-00001"),
	 * 		   	   @OA\Property(property="payee", type="string", example="Maybank"),
	 *             @OA\Property(property="particulars", type="text", example="FORMATTED BY: PAYMENT FOR [*MODE OF PAYROLL], [*MODE OF PAYROLL RELEASE] for the Period Covered: [*DATE OF PAYROLL PERIOD COVERED]"),
	 *             @OA\Property(property="net_amount", type="integer", example="5000"),
	 * 			   @OA\Property(property="date_encoded", type="date:Y-m-d", example="2024-10-11"),
	 * 	           @OA\Property(property="voucher_date", type="date:Y-m-d", example="2024-10-11"),
	 * 			   @OA\Property(property="status", type="string", example="pending")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="New Voucher",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="voucher_no", type="string", example="DV-202402-00001"),
	 * 		   	   @OA\Property(property="payee", type="string", example="Maybank"),
	 *             @OA\Property(property="particulars", type="text", example="FORMATTED BY: PAYMENT FOR [*MODE OF PAYROLL], [*MODE OF PAYROLL RELEASE] for the Period Covered: [*DATE OF PAYROLL PERIOD COVERED]"),
	 *             @OA\Property(property="net_amount", type="integer", example="5000"),
	 * 			   @OA\Property(property="date_encoded", type="date:Y-m-d", example="2024-10-11"),
	 * 	           @OA\Property(property="voucher_date", type="date:Y-m-d", example="2024-10-11"),
	 * 			   @OA\Property(property="status", type="string", example="pending")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="unauthorized"
	 *     ),
	 * 	   @OA\Response(
	 *		   response=422,
	*		   description="Validation Error",
	*		   @OA\JsonContent(
	*		       type="object",
	*			   @OA\Property(property="message", type="string"),
	*			   @OA\Property(property="errors", type="object")
	*			)
	*		)
	* 
	* )
	*/
    public function store(StoreVoucherRequest $request)
    {

        Voucher::create(request->validated());

        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'message' => "New Voucher",
        ], JsonResponse::HTTP_CREATED);
    }

	/**
	 *     @OA\Get(
	 *     path="/api/v1/voucher/{id}",
	 *     summary="Get an existing voucher",
	 *     tags={"Get a voucher"},
	 * 	   @OA\Parameter(
	 *         name="Authorization",
	 *         in="header",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string",
	 *             default="Bearer {token}"
	 *         ),
	 *         description="Authorization token"
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Get an existing voucher",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="voucher_no", type="string", example="DV-202402-00001"),
	 * 		   	   @OA\Property(property="payee", type="string", example="Maybank"),
	 *             @OA\Property(property="particulars", type="text", example="FORMATTED BY: PAYMENT FOR [*MODE OF PAYROLL], [*MODE OF PAYROLL RELEASE] for the Period Covered: [*DATE OF PAYROLL PERIOD COVERED]"),
	 *             @OA\Property(property="net_amount", type="integer", example="5000"),
	 * 			   @OA\Property(property="date_encoded", type="date:Y-m-d", example="2024-10-11"),
	 * 	           @OA\Property(property="voucher_date", type="date:Y-m-d", example="2024-10-11"),
	 * 			   @OA\Property(property="status", type="string", example="pending")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="unauthorized"
	 *     )
	* 
	* )
	*/
    public function show(Voucher $voucher)
    {
		return response->json([
			'message' => 'Resource',
			'voucher' => new VoucherResource($voucher)
		]);
    }

	/**
	 *     @OA\Patch(
	 *     path="/api/v1/voucher/{id}",
	 *     summary="Update an existing voucher",
	 *     tags={"Update a voucher"},
	 * 	   @OA\Parameter(
	 *         name="Authorization",
	 *         in="header",
	 *         required=true,
	 *         @OA\Schema(
	 *             type="string",
	 *             default="Bearer {token}"
	 *         ),
	 *         description="Authorization token"
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             @OA\Property(property="voucher_no", type="string", example="DV-202402-00001"),
	 * 		   	   @OA\Property(property="payee", type="string", example="Maybank"),
	 *             @OA\Property(property="particulars", type="text", example="FORMATTED BY: PAYMENT FOR [*MODE OF PAYROLL], [*MODE OF PAYROLL RELEASE] for the Period Covered: [*DATE OF PAYROLL PERIOD COVERED]"),
	 *             @OA\Property(property="net_amount", type="integer", example="5000"),
	 * 			   @OA\Property(property="date_encoded", type="date:Y-m-d", example="2024-10-11"),
	 * 	           @OA\Property(property="voucher_date", type="date:Y-m-d", example="2024-10-11"),
	 * 			   @OA\Property(property="status", type="string", example="pending")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="Voucher has been updated",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="voucher_no", type="string", example="DV-202402-00001"),
	 * 		   	   @OA\Property(property="payee", type="string", example="Maybank"),
	 *             @OA\Property(property="particulars", type="text", example="FORMATTED BY: PAYMENT FOR [*MODE OF PAYROLL], [*MODE OF PAYROLL RELEASE] for the Period Covered: [*DATE OF PAYROLL PERIOD COVERED]"),
	 *             @OA\Property(property="net_amount", type="integer", example="5000"),
	 * 			   @OA\Property(property="date_encoded", type="date:Y-m-d", example="2024-10-11"),
	 * 	           @OA\Property(property="voucher_date", type="date:Y-m-d", example="2024-10-11"),
	 * 			   @OA\Property(property="status", type="string", example="pending")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="unauthorized"
	 *     ),
	 * 	   @OA\Response(
	 *		   response=422,
	*		   description="Validation Error",
	*		   @OA\JsonContent(
	*		       type="object",
	*			   @OA\Property(property="message", type="string"),
	*			   @OA\Property(property="errors", type="object")
	*			)
	*		)
	* 
	* )
	*/
    public function update(Request $request, Voucher $voucher)
    {
		$validated = $request->validate([
			'voucher_no' => ['required', 'string'],
			'particulars' ['nullable|string'],
			'net_amount' => ['required', 'numeric'],
			'amount_in_words' => ['nullable', 'string'],
			'date_encoded' => ['required','date','date_format:Y-m-d'],
			'voucher_date' => ['required','date','date_format:Y-m-d'],
			'status' => ['required', 'string']
		]);

		$voucher->update($validated);

		return response->json([
			'message' => 'Voucher has been updated',
			'voucher' => new VoucherResource($voucher)
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
       
    }
}
