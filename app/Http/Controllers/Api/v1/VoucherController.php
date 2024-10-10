<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\v1\Store\StoreVoucherRequest;
use App\Http\Requests\Api\v1\Update\UpdateVoucherRequest;
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
	 *     tags={"Get all vouchers"},
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
	 * 			   @OA\Property(property="status", type="string", example="pending"),
	 * 			   @OA\Property(
	 * 					property="line_items", 
	 * 					description="Business ID", 
	 *                  type="array", 
	 * 					collectionFormat="multi", 
	 * 					@OA\Items(
	 * 						type="object",
	 * 						@OA\Property(property="account_id", type="int", example="10"),
	 * 						@OA\Property(property="contact", type="string", example="Contact"),
	 * 						@OA\Property(property="debit", type="decimal", example="500.00"),
	 * 						@OA\Property(property="credit", type="decimal", example="0.00"),
	 * 					),
	 * 				)
	 * 		
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
		return response()->json([
			'vouchers' => VoucherResource::collection(Voucher::all())
		]);
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
	 * 			   @OA\Property(property="status", type="string", example="pending"),
	 * 				@OA\Property(
	 * 					property="line_items", 
	 * 					description="Business ID", 
	 *                  type="array", 
	 * 					collectionFormat="multi", 
	 * 					@OA\Items(
	 * 						type="object",
	 * 						@OA\Property(property="account_id", type="int", example="10"),
	 * 						@OA\Property(property="contact", type="string", example="Contact"),
	 * 						@OA\Property(property="debit", type="decimal", example="500.00"),
	 * 						@OA\Property(property="credit", type="decimal", example="0.00"),
	 * 					),
	 * 				)
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
	 * 			   @OA\Property(property="status", type="string", example="pending"),
	 * 				@OA\Property(
	 * 					property="line_items", 
	 * 					description="Business ID", 
	 *                  type="array", 
	 * 					collectionFormat="multi", 
	 * 					@OA\Items(
	 * 						type="object",
	 * 						@OA\Property(property="account_id", type="int", example="10"),
	 * 						@OA\Property(property="contact", type="string", example="Contact"),
	 * 						@OA\Property(property="debit", type="decimal", example="500.00"),
	 * 						@OA\Property(property="credit", type="decimal", example="0.00"),
	 * 					),
	 * 				)
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

        $voucher = Voucher::create($request->validated());
		$voucher->Items()->createMany($request->line_items);

        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
			'voucher' => new VoucherResource($voucher),
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
	 * 			   @OA\Property(property="status", type="string", example="pending"),
	 * 				@OA\Property(
	 * 					property="line_items", 
	 * 					description="Business ID", 
	 *                  type="array", 
	 * 					collectionFormat="multi", 
	 * 					@OA\Items(
	 * 						type="object",
	 * 						@OA\Property(property="account_id", type="int", example="10"),
	 * 						@OA\Property(property="contact", type="string", example="Contact"),
	 * 						@OA\Property(property="debit", type="decimal", example="500.00"),
	 * 						@OA\Property(property="credit", type="decimal", example="0.00"),
	 * 					),
	 * 				)
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
		return response()->json([
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
	 * 			   @OA\Property(property="status", type="string", example="pending"),
	 * 				@OA\Property(
	 * 					property="line_items", 
	 * 					description="Business ID", 
	 *                  type="array", 
	 * 					collectionFormat="multi", 
	 * 					@OA\Items(
	 * 						type="object",
	 * 						@OA\Property(property="account_id", type="int", example="10"),
	 * 						@OA\Property(property="contact", type="string", example="Contact"),
	 * 						@OA\Property(property="debit", type="decimal", example="500.00"),
	 * 						@OA\Property(property="credit", type="decimal", example="0.00"),
	 * 					),
	 * 				)
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
	 * 			   @OA\Property(property="status", type="string", example="pending"),
	 * 				@OA\Property(
	 * 					property="line_items", 
	 * 					description="Business ID", 
	 *                  type="array", 
	 * 					collectionFormat="multi", 
	 * 					@OA\Items(
	 * 						type="object",
	 * 						@OA\Property(property="account_id", type="int", example="10"),
	 * 						@OA\Property(property="contact", type="string", example="Contact"),
	 * 						@OA\Property(property="debit", type="decimal", example="500.00"),
	 * 						@OA\Property(property="credit", type="decimal", example="0.00"),
	 * 					),
	 * 				)
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
    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
		$voucher->update($request->validated());

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
