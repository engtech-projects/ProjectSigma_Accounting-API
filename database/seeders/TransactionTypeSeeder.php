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
            ],
            [
                'transaction_type_name' => 'IPR',
                'book_id' => 2,
                'account_id' => 1
            ],
        ];


        foreach ($transTypeSeed as $value) {
            TransactionType::create([
                'transaction_type_name' => $value['transaction_type_name'],
                'book_id' => $value['book_id'],
                'account_id' => $value['account_id']
            ]);
        }

    }
}
