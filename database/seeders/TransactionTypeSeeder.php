<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transTypeSeed = [
            [
                'transaction_type_name' => 'Journal Entry',
                'book_id' => 2,
                'symbol' => 'JE',
                'stakeholder_group_id' => 1
                'origin_account' => null
            ],
            [
                'transaction_type_name' => 'Payroll Summary',
                'book_id' => 3,
                'symbol' => 'PS'
                'stakeholder_group_id' => 1
                'origin_account' => 7
            ],
        ];


        foreach ($transTypeSeed as $value) {
            TransactionType::create([
                'transaction_type_name' => $value['transaction_type_name'],
                'book_id' => $value['book_id'],
                'symbol' => $value['symbol']
                'stakeholder_group_id' => $value['stakeholder_group_id'],
                'origin_account' => $value['origin_account']
            ]);
        }

    }
}
