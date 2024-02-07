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
                'book_code' => 'DSBRSMNTBK-02072024',
                'book_name' => 'Disbursement',
                'book_src' => 'Book Source 01',
                'book_ref' => 'Book Reference 01',
                'book_flag' => 'Book Flag 01',
                'book_head' => 'Book Head 01',
            ],
            [
                'book_code' => 'DSBRSMNTBK-02072024',
                'book_name' => 'Cash Source',
                'book_src' => 'Book Source 02',
                'book_ref' => 'Book Reference 02',
                'book_flag' => 'Book Flag 02',
                'book_head' => 'Book Head 02',
            ],
        ];


        foreach ($bookSeed as $book) {
            Book::create([
                'book_code' => $book['book_code'],
                'book_name' => $book['book_name'],
                'book_src' => $book['book_src'],
                'book_ref' => $book['book_ref'],
                'book_flag' => $book['book_flag'],
                'book_head' => $book['book_head'],
            ]);
        }
    }
}
