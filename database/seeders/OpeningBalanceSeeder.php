<?php

namespace Database\Seeders;

use App\Models\OpeningBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpeningBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $openingBalanceSeed = [
            [
                'period_id' => 1,
                'account_id' => 1,
            ],
            [
                'period_id' => 1,
                'account_id' => 2,
            ],
            [
                'period_id' => 1,
                'account_id' => 3,
            ],
            [
                'period_id' => 1,
                'account_id' => 4,
            ],
            [
                'period_id' => 1,
                'account_id' => 5,
            ],
            [
                'period_id' => 1,
                'account_id' => 5,
            ],
        ];

        foreach ($openingBalanceSeed as $value) {
            $randomBalance = rand(1, 1000);
            OpeningBalance::create([
                'opening_balance' => $randomBalance,
                'remaining_balance' => $randomBalance,
                'account_id' => $value['account_id'],
                'period_id' => $value['period_id']
            ]);
        }
    }


}
