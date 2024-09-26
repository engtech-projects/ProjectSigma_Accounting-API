<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\v1\Store\StoreVoucherRequest;
use Illuminate\Http\JsonResponse;

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
     * @OA\Post(
 *     path="/api/voucher",
 *     summary="Create a Voucher",
 *     tags={"Voucher"},
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
 *         description="Voucher has been created",
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
    public function store(StoreVoucherRequest $request) : JsonResponse
    {

        Voucher::create(reques->validated());

        return response()->json([
            'status' => JsonResponse::HTTP_CREATED,
            'message' => "Transaction successfully created.",
        ], JsonResponse::HTTP_CREATED);
    }
}
