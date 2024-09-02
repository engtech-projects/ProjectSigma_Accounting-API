<?php

namespace Database\Seeders;

use App\Enums\BookStatus;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $bookSeed = [
            [
                'book_name' => 'Disbursement Voucher',
                'account_group_id' => 1
                'symbol' => 'DV'
            ],
            [
                'book_name' => 'Payroll Voucher',
                'account_group_id' => 1
                'symbol' => 'PV'
            ],
            [
                'book_name' => 'Journal Voucher',
                'account_group_id' => 1
                'symbol' => 'JV'
            ],
        ];


        foreach ($bookSeed as $book) {
            $book = Book::create([
                'book_name' => $book['book_name'],
                'symbol' => $book['symbol'],
                'account_group_id' => $book['account_group_id'],
            ]);

            $book->accounts()->attach([1]);

        }
    }
}
