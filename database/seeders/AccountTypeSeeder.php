<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    protected $typeSeeds = [
        [
            'type_name' => 'CURRENT ASSETS',
            'type_number' => '1000',
            'category_id' => 1,
        ],
        [
            'type_name' => "STOCKHOLDER'S EQUITY",
            'type_number' => '3000',
            'category_id' => 3,
        ],
        [
            'type_name' => 'OPERATING EXPENSES',
            'type_number' => '5000',
            'category_id' => 5,
        ],
        [
            'type_name' => 'OTHER INCOME',
            'type_number' => '4000',
            'category_id' => 4,
        ],
        [
            'type_name' => 'CURRENT LIABILITIES',
            'type_number' => '2000',
            'category_id' => 2,
        ],
    ];

    public function run(): void
    {
        foreach ($this->typeSeeds as $value) {
            AccountType::create([
                'type_name' => $value['type_name'],
                'type_number' => $value['type_number'],
                'category_id' => $value['category_id'],
            ]);
        }
    }
}
