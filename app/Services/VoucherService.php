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
					// 'remarks' => $voucher->particulars,
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
						'journal_id' => $journal->id,
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

}