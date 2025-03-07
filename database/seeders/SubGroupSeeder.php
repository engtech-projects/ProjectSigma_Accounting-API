<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class SubGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            'CURRENT ASSETS',
            'NONCURRENT ASSETS',
            'CAPITAL OUTLAY',
            "PARTNER'S EQUITY",
            'COST OF SALES- CONSTRUCTION',
            'MAINTENANCE AND OTHER OPERATING EXPENSES',
            'PERSONAL SERVICES',
            'CONSTRUCTION COST- SUBCONTRACTORS',
            'CONSTRUCTION COST- LABOR',
            'CONSTRUCTION COST- DIRECT OVERHEAD',
            'CONSTRUCTION COST- DEPRECIATION AND AMORTIZATION',
            'CONSTRUCTION COST- OVERHEAD',
            'CONSTRUCTION COST- MATERIALS',
            'CONSTRUCTION COST- EQUIPMENT RENTAL',
            'MARKETING EXPENSES',
            'GENERAL AND ADMINISTRATIVE EXPENSES',
            'FINANCIAL EXPENSES',
            'OPERATING EXPENSES',
            'CURRENT LIABILITIES',
            'NONCURRENT LIABILITIES',
            'OTHER CURRENT LIABILITIES',
            'REVENUE',
        ];

        $now = Carbon::now();
        $data = array_map(function ($index, $account) use ($now) {
            return [
                'id' => $index + 1,
                'name' => $account,
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, array_keys($accounts), $accounts);

        DB::table('sub_groups')->insert($data);

    }
}
