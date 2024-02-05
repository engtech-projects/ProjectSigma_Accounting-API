<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private $accountSeeds = [
        [
            'account_number' => "1000",
            'account_name' => "CURRENT ASSETS",
            'status' => "active",
            'type_id' => 1,
        ],


        [
            'account_number' => "1005",
            'account_name' => "CASH AND CASH EQUIVALENTS	CASH AND CASH EQUIVALENTS",
            'status' => "active",
            'type_id' => 2,
        ],

        [
            'account_number' => "1010",
            'account_name' => "Cash on Hand Cash on Hand",
            'status' => "active",
            'type_id' => 2,
            'type' => "L",
        ],

        [
            'account_number' => "1015",
            'account_name' => "Check and Other Cash Items (COCI)	Check and Other Cash Items (COCI)",
            'status' => "active",
            'type_id' => 2,
            'type' => "L",
        ],
        [
            'account_number' => "1020",
            'account_name' => "Petty Cash Fund	Petty Cash Fund",
            'status' => "active",
            'type_id' => 2,
            'type' => "L"
        ],
        [
            'account_number' => "1025",
            'account_name' => "Cash in Bank (EWB)	Cash in Bank (EWB)",
            'status' => "active",
            'type_id' => 2,
            'type' => "L"
        ],
    ];
    public function run(): void
    {
        foreach ($this->accountSeeds as $account) {
            Account::create([
                'account_number' => $account['account_number'],
                'account_name' => $account['account_name'],
                'status' => $account['status'],
                'type_id' => $account['type_id'],
            ]);
        }
    }
}
