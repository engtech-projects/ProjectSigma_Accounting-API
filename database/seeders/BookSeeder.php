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
                'book_name' => 'Disbursement',
                'symbol' => 'DSB'
            ],
            [
                'book_name' => 'Cash Source',
                'symbol' => 'CHS'
            ],
        ];


        foreach ($bookSeed as $book) {
            $book = Book::create([
                'book_name' => $book['book_name'],
                'symbol' => $book['symbol'],
            ])->book_accounts()
                ->attach([1]);

        }
    }
}
