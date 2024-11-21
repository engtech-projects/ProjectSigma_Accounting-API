<?php

namespace App\Services;

use App\Http\Resources\AccountingCollections\PaymentRequestCollection;
use App\Http\Resources\VoucherResource;
use App\Models\Book;
use App\Models\PaymentRequest;
use App\Models\Voucher;
use App\Models\JournalEntry;
use App\Models\PostingPeriod;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Str;

class VoucherService
{
	public function create(array $attributes)
	{
		DB::beginTransaction();

		try {
			$postingPeriodId = PostingPeriod::current()->pluck('id')->first();
			$periodId = Period::where('posting_period_id', $postingPeriodId)->current()->pluck('id')->first();

			$voucher = Voucher::create($attributes);
			$voucher->details()->createMany($attributes['details']);

			if( !$voucher->check_no )
			{
				$journal = JournalEntry::create([
					'journal_no' => 'JE-' . $voucher->voucher_no,
					'journal_date' => Carbon::now()->format('Y-m-d'),
					'voucher_id' => $voucher->id,
					'status' => 'open',
					'posting_period_id' => $postingPeriodId,
					'period_id' => $periodId
				]);

				$journal->details()->create([
					'journal_entry_id' => $journal->id,
					'account_id' => $voucher->account_id,
					'stakeholder_id' => $voucher->stakeholder_id,
					'debit' => 0.00,
					'credit' => $voucher->net_amount,
				]);

				foreach( $voucher->details()->get() as $details )
				{
					$journal->details()->create([
						'journal_entry_id' => $journal->id,
						'account_id' => $details->account_id,
						'stakeholder_id' => $details->stakeholder_id,
						'debit' => $details->debit,
						'credit' => $details->credit,
					]);
				}
			}

			DB::commit();

			return $voucher;

		} catch (\Exception $e) {
			DB::rollBack(); // Rollback if something fails
			return response()->json($e, 500);
		}
	}
    public static function generateVoucherNo($prefix)
	{
		$prefix = Str::upper($prefix);
		$currentYearMonth = Carbon::now()->format('Ym');
        // Find the highest series number based on the prefix:DV/CV
        $lastVoucher = Voucher::where('voucher_no', 'like', "{$prefix}-{$currentYearMonth}-%")
            ->orderBy('voucher_no', 'desc')
            ->first();
        // Extract the last series number if a previous voucher exists
        if ($lastVoucher) {
            $lastSeries = (int) substr($lastVoucher->voucher_no, -4);
            $nextSeries = $lastSeries + 1;
        } else {
            $nextSeries = 1; // Start at 0001 if no previous voucher
        }
        // Format the series number to be 4 digits (e.g., 0001)
        $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
        // Construct the new reference number
        $voucherNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";

        return $voucherNo;
	}
    public static function getWithPagination(array $validatedData)
    {
        $query = Voucher::query();
        if( isset($validatedData['book']) )
		{
			$book = Book::byName($validatedData['book'])->firstOr(function () {
				return Book::first();
			});
			if( $book ) {
				$query->filterBook($book->id);
			}
		}
		if( isset($validatedData['status']) ) {
            $query->status($validatedData['status']);
		}
        $voucherRequest =  $query->latest('id')
            ->with(['account','stakeholder', 'details'])
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
    public static function getWithPaginationDisbursement(array $validatedData)
    {
        $query = Voucher::query();
        if( isset($validatedData['book']) )
		{
			$book = Book::byName($validatedData['book'])->firstOr(function () {
				return Book::first();
			});
			if( $book ) {
				$query->filterBook($book->id);
			}
		}
		if( isset($validatedData['status']) ) {
            $query->status($validatedData['status']);
		}
        $voucherRequest =  $query->latest('id')
            ->with(['account','stakeholder', 'details'])
            ->whereDisbursement()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
    public static function myApprovalsDisbursement()
    {
        $voucherRequest =  Voucher::with(['account','stakeholder', 'details'])
            ->myApprovals()
            ->whereDisbursement()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
    public static function myRequestDisbursement()
    {
        $voucherRequest = Voucher::with(['account','stakeholder', 'details'])
            ->withPaymentRequestDetails()
            ->whereDisbursement()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
    public static function myVoucheringDisbursement()
    {
        $paymentRequest = PaymentRequest::myApprovals()
            ->withStakeholder()
            ->isApproved()
            ->withPaymentRequestDetails()
            ->paginate(config('services.pagination.limit'));
        return PaymentRequestCollection::collection($paymentRequest)->response()->getData(true);
    }
    public static function getWithPaginationCash(array $validatedData)
    {
        $query = Voucher::query();
        if( isset($validatedData['book']) )
		{
			$book = Book::byName($validatedData['book'])->firstOr(function () {
				return Book::first();
			});
			if( $book ) {
				$query->filterBook($book->id);
			}
		}
		if( isset($validatedData['status']) ) {
            $query->status($validatedData['status']);
		}
        $voucherRequest =  $query->latest('id')
            ->with(['account','stakeholder', 'details'])
            ->whereCash()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
    public static function myApprovalsCash()
    {
        $voucherRequest =  Voucher::with(['account','stakeholder', 'details'])
            ->myApprovals()
            ->whereCash()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
    public static function myRequestCash()
    {
        $voucherRequest = Voucher::with(['account','stakeholder', 'details'])
            ->withPaymentRequestDetails()
            ->whereCash()
            ->orderDesc()
            ->paginate(config('services.pagination.limit'));
        return VoucherResource::collection($voucherRequest)->response()->getData(true);
    }
}
