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
            'account_number' => "1000,	",
            'account_name' => "CURRENT ASSETS",
            'bank_reconciliation' => "no",
            'status' => "active",
            'type_id' => 1,
            'type' => "H",
        ],


        [
            'account_number' => "1005",
            'account_name' => "CASH AND CASH EQUIVALENTS	CASH AND CASH EQUIVALENTS",
            'bank_reconciliation' => "no",
            'status' => "active",
            'type_id' => 2,
            'type' => "H",
        ],

        [
            'account_number' => "1010",
            'account_name' => "Cash on Hand Cash on Hand",
            'bank_reconciliation' => "yes",
            'status' => "active",
            'type_id' => 2,
            'type' => "L",
        ],

        [
            'account_number' => "1015",
            'account_name' => "Check and Other Cash Items (COCI)	Check and Other Cash Items (COCI)",
            'bank_reconciliation' => "no",
            'status' => "active",
            'type_id' => 2,
            'type' => "L",
        ],

        [
            'account_number' => "1020",
            'account_name' => "Petty Cash Fund	Petty Cash Fund",
            'bank_reconciliation' => "yes",
            'status' => "active",
            'type_id' => 2,
            'type' => "L"
        ],
        [
            'account_number' => "1025",
            'account_name' => "Cash in Bank (EWB)	Cash in Bank (EWB)",
            'bank_reconciliation' => "yes",
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
                'bank_reconciliation' => $account['bank_reconciliation'],
                'status' => $account['status'],
                'type_id' => $account['type_id'],
                'type' => $account['type']
            ]);
        }
    }
}
