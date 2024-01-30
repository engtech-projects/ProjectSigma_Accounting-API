<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    protected $typeSeeds = [
        [
            'account_type' => 'CURRENT ASSETS',
            'account_type_number' => '1000',
            'account_category_id' => 1,
        ],
        [
            'account_type' => "STOCKHOLDER'S EQUITY",
            'account_type_number' => '3000',
            'account_category_id' => 3,
        ],
        [
            'account_type' => 'OPERATING EXPENSES',
            'account_type_number' => '5000',
            'account_category_id' => 5,
        ],
        [
            'account_type' => 'OTHER INCOME',
            'account_type_number' => '4000',
            'account_category_id' => 4,
        ],
        [
            'account_type' => 'CURRENT LIABILITIES',
            'account_type_number' => '2000',
            'account_category_id' => 2,
        ],
    ];

    public function run(): void
    {
        foreach ($this->typeSeeds as $value) {
            AccountType::create([
                'account_type' => $value['account_type'],
                'account_type_number' => $value['account_type_number'],
                'account_category_id' => $value['account_category_id'],
            ]);
        }
    }
}
