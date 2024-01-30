<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    protected $categoriesSeeds = [
        [
            'account_category' => 'assets',
            'to_increase' => 'debit',
        ],
        [
            'account_category' => 'liabilites',
            'to_increase' => 'credit',
        ],
        [
            'account_category' => 'equity',
            'to_increase' => 'credit',
        ],
        [
            'account_category' => 'income',
            'to_increase' => 'credit',
        ],
        [
            'account_category' => 'expense',
            'to_increase' => 'debit',
        ],

    ];
    public function run(): void
    {
        foreach ($this->categoriesSeeds as $value) {
            AccountCategory::create([
                'account_category' => $value['account_category'],
                'to_increase' => $value['to_increase'],
            ]);
        }
    }
}
