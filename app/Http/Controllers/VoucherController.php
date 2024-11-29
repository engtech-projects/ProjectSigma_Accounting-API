<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatuses;
use App\Enums\VoucherType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCashVoucherRequest;
use App\Http\Requests\CreateDisbursementVoucherRequest;
use App\Http\Requests\VoucherRequestFilter;
use App\Models\Book;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use App\Models\Voucher;
use App\Http\Resources\VoucherResource;
use App\Http\Requests\UpdateRequest\VoucherUpdateRequest;
use App\Services\VoucherService;
use App\Enums\VoucherStatus;
use Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VoucherRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Vouchers fetched',
            'data' => VoucherService::getWithPagination($request->validated()),
        ], 201);
    }
    public function disbursementAllRequest(VoucherRequestFilter $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Vouchers fetched',
            'data' => VoucherService::getWithPaginationDisbursement($request->validated()),
        ], 201);
    }
    public function disbursementMyRequest()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Disbursement Voucher My Requests Successfully Retrieved.',
            'data' => VoucherService::myRequestDisbursement(),
        ], 200);
    }
    public function disbursementMyApprovals()
    {
        $myApprovals = VoucherService::myApprovalsDisbursement();
        return new JsonResponse([
            "success" => true,
            "message" => "Disbursement Voucher My Approvals Successfully Retrieved.",
            "data" => $myApprovals
        ], 200);
    }
    public function disbursementMyVouchering()
    {
        $myvouchering = VoucherService::myVoucheringDisbursement();
        return new JsonResponse([
            "success" => true,
            "message" => "Disbursement Voucher My Approvals Successfully Retrieved.",
            "data" => $myvouchering
        ], 200);
    }

    public function cashAllRequest(Request $request)
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Vouchers fetched',
            'data' => VoucherService::getWithPaginationCash($request->validated()),
        ], 201);
    }
    public function cashMyRequest()
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Cash Voucher My Requests Successfully Retrieved.',
            'data' => VoucherService::myRequestCash(),
        ], 200);
    }
    public function cashMyApprovals()
    {
        $myApprovals = VoucherService::myApprovalsCash();
        return new JsonResponse([
            "success" => true,
            "message" => "Cash Voucher My Approvals Successfully Retrieved.",
            "data" => $myApprovals
        ], 200);
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
    public function createCash(CreateCashVoucherRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $validatedData['type'] = VoucherType::CASH->value;
            $voucher = Voucher::create($validatedData);
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Voucher created',
                'data' => $voucher,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'success' => false,
                'message' => 'Voucher creation failed',
            ], 500);
        }
    }
    public function createDisbursement(CreateDisbursementVoucherRequest $request)
    {
        DB::beginTransaction();
        // try {
            $validatedData = $request->validated();
            $validatedData['type'] = VoucherType::DISBURSEMENT->value;
            $validatedData['book_id'] = Book::where('code', VoucherType::DISBURSEMENT_CODE->value)->first()->id;
            $validatedData['status'] = VoucherStatus::PENDING->value;
            $validatedData['date_encoded'] = Carbon::now();
            $validatedData['request_status'] = RequestStatuses::PENDING->value;
            $voucher = Voucher::create($validatedData);
            foreach($validatedData['details'] as $detail) {
                $voucher->details()->create([
                    'account_id' => $detail['account_id'],
                    'stakeholder_id' => $detail['stakeholder_id'] ?? null,
                    'description' => $detail['description'] ?? null,
                    'debit' => $detail['debit'] ?? null,
                    'credit' => $detail['credit'] ?? null,
                ]);
            }
            DB::commit();
            return new JsonResponse([
                'success' => true,
                'message' => 'Voucher created',
                'data' => $voucher,
            ], 201);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return new JsonResponse([
        //         'success' => false,
        //         'message' => 'Voucher creation failed',
        //     ], 500);
        // }
    }


    /**voucher
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucher = Voucher::find($id)->with(['stakeholder', 'account', 'book', 'details']);
		return new JsonResponse([
			'success' => true,
			'message' => 'Voucher Successfully Retrieved.',
			'data' => $voucher,
		], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherUpdateRequest $request, Voucher $voucher)
    {
        $voucher->update($request->validated());

		// Get current voucher details
		$existingIds = $voucher->details()->pluck('id')->toArray();

		$voucherDetails = $request->details;
		$incomingIds = [];

		foreach ($voucherDetails as $voucherDetail)
		{
			$detail = $voucher->details()->updateOrCreate($voucherDetail);
			$incomingIds[] = $detail->id;
		}
		// Remove voucher details that are no longer present
		$toDelete = array_diff($existingIds, $incomingIds);
		$voucher->details()->whereIn('id', $toDelete)->delete();
		return response()->json(new VoucherResource($voucher), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
	public function voucherNo($prefix = 'DV')
	{
		try {
			return new JsonResponse([
				'success' => true,
				'message' => 'Voucher number generated',
				'data' => Voucher::generateVoucherNo($prefix),
			], 201);
		} catch (\Exception $e) {
			return new JsonResponse([
				'success' => false,
				'message' => "Voucher number generation failed",
			], 500);
		}
	}

	public function changeStatus(int $id, VoucherStatus $status)
	{
		$voucher = Voucher::find($id);
		if (!$voucher) {
			return new JsonResponse([
				'success' => false,
				'message' => 'Voucher not found',
				'data' => null,
			], 404);
		}
		// Attempt to update status
		if ($voucher->updateStatus($status)) {
			return new JsonResponse([
				'success' => true,
				'message' => 'Voucher status updated',
				'data' => $voucher,
			], 200);
		} else {
			return new JsonResponse([
				'success' => false,
				'message' => 'Transition not allowed',
				'data' => $voucher,
			], 405);
		}
	}
	public function disbursementGenerateVoucherNumber()
	{
		return new JsonResponse([
			'success' => true,
			'message' => 'Voucher number generated',
			'data' => VoucherService::generateVoucherNo('DV'),
		], 201);
	}
	public function cashGenerateVoucherNumber()
	{
		return new JsonResponse([
			'success' => true,
			'message' => 'Voucher number generated',
			'data' => VoucherService::generateVoucherNo('CV'),
		], 201);
	}
}
