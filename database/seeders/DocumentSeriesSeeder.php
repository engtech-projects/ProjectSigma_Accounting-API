<?php

namespace Database\Seeders;

use App\Models\DocumentSeries;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentSeriesSeed = [
            [
                'series_scheme' => 'CDB-00000',
                'series_description' => 'Series Description',
                'status' => 'active',
                'next_number' => 1,
                'transaction_type_id' => 1
            ]
        ];

        foreach ($documentSeriesSeed as $value) {
            DocumentSeries::create([
                'series_scheme' => $value['series_scheme'],
                'series_description' => $value['series_description'],
                'status' => $value['status'],
                'next_number' => $value['next_number'],
                'transaction_type_id' => $value['transaction_type_id']
            ]);
        }
    }
}
