<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    protected $categoriesSeeds = [
        [
            'category_name' => 'assets',
            'to_increase' => 'debit',
        ],
        [
            'category_name' => 'liabilites',
            'to_increase' => 'credit',
        ],
        [
            'category_name' => 'equity',
            'to_increase' => 'credit',
        ],
        [
            'category_name' => 'income',
            'to_increase' => 'credit',
        ],
        [
            'category_name' => 'expense',
            'to_increase' => 'debit',
        ],

    ];
    public function run(): void
    {
        foreach ($this->categoriesSeeds as $value) {
            AccountCategory::create([
                'category_name' => $value['category_name'],
                'to_increase' => $value['to_increase'],
            ]);
        }
    }
}
