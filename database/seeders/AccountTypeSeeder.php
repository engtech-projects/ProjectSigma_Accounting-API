<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    protected $typeSeeds = [
        [
            'account_type' => 'Account Receivable',
            'account_category' => 'asset',
            'balance_type' =>  'debit',
            'notation' => '+'
        ],
        [
            'account_type' => 'Equity',
            'account_category' => 'equity',
            'balance_type' =>  'credit',
            'notation' => '-'
        ],
        [
            'account_type' => 'Other Expense',
            'account_category' => 'expenses',
            'balance_type' =>  'debit',
            'notation' => '+'
        ],
        [
            'account_type' => 'Income',
            'account_category' => 'income',
            'balance_type' =>  'credit',
            'notation' => '-'
        ],
        [
            'account_type' => 'Other Current Liabilty',
            'account_category' => 'liability',
            'balance_type' =>  'credit',
            'notation' => '-'
        ],
    ];

    public function run(): void
    {
        foreach ($this->typeSeeds as $value) {
            AccountType::create([
                'account_type' => $value['account_type'],
                'account_category' => $value['account_category'],
                'balance_type' => $value['balance_type'],
                'notation' => $value['notation']
            ]);
        }
    }
}
