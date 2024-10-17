<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\JournalEntry;
use App\Models\PostingPeriod;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherService
{
	public function createVoucher(array $attributes)
	{
		DB::beginTransaction(); // Start transaction

		try {
			$postingPeriodId = PostingPeriod::current()->pluck('id')->first();
			$periodId = Period::where('posting_period_id', $postingPeriodId)->current()->pluck('id')->first();
			
			$voucher = Voucher::create($attributes);
			$voucher->details()->createMany($attributes['details']);
			
			$journal = JournalEntry::create([
				'journal_no' => 'JE-' . $voucher->voucher_no,
				'journal_date' => Carbon::now()->format('Y-m-d'),
				'voucher_id' => $voucher->voucher_id,
				'status' => 'open',
				'remarks' => $voucher->particulars,
				'posting_period_id' => $postingPeriodId,
				'period_id' => $periodId
			]);

			DB::commit();

		} catch (\Exception $e) {
			DB::rollBack(); // Rollback if something fails
			return response()->json(['error' => 'Error creating voucher and journal entry'], 500);
		}
	}

	public function updateVoucher(array $attributes)
	{
		
	}

}