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
                'transaction_type_name' => 'Inventroy Purchase',
                'book_id' => 1,
                'account_id' => 1,
                'symbol' => 'IVP'
            ],
            [
                'transaction_type_name' => 'Test Transaction Type Name',
                'book_id' => 2,
                'account_id' => 1,
                'symbol' => 'TTN'
            ],
        ];


        foreach ($transTypeSeed as $value) {
            TransactionType::create([
                'transaction_type_name' => $value['transaction_type_name'],
                'book_id' => $value['book_id'],
                'account_id' => $value['account_id'],
                'symbol' => $value['symbol']
            ]);
        }

    }
}
