<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubAccountGroup extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_groups')->delete();
        $subAccountGroupData = [
            "Current Assets",
            "Noncurrent Assets",
            "Capital Outlay",
            "Partner's Equity",
            "Cost of Sales - Construction",
            "Construction Cost- Subcontractors",
            "Personal Services",
            "Construction Cost- Labor",
            "Maintenance and other Operating Expenses",
            "Marketing Expenses",
            "Construction Cost- Direct Overhead",
            "Construction Cost - Depreciation and Amortization",
            "Construction Cost- Overhead",
            "Construction Cost- Materials",
            "Construction Cost- Equipment Rental",
            "General and Administrative Expenses",
            "Financial Expenses",
            "Operating Expenses",
            "Current Liabilities",
            "Other Current Liabilities",
            "Noncurrent Liabilities",
            "Revenue",
        ];

        $now = Carbon::now();
        $data = array_map(function ($index, $account) use ($now) {
            return [
                'id' => $index + 1,
                'name' => $account,
                'description' => $account,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, array_keys($subAccountGroupData), $subAccountGroupData);

        DB::table('sub_groups')->insert($data);
    }
}
