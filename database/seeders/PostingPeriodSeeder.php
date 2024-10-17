<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\PostingPeriod;
use App\Models\Period;

class PostingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$currentDate = Carbon::now();

        $postingPeriod = PostingPeriod::create([
			'period_start' => $currentDate->copy()->startOfYear(),
			'period_end' => $currentDate->copy()->endOfYear(),
		]);


		for ($month = 1; $month <= 12; $month++) {

			$startOfMonth = Carbon::createFromDate($currentDate->year, $month, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

			$postingPeriod->periods()->create([
				
				'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
			]);
		}
		

    }
}
