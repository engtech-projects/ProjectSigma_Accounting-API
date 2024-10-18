<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StakeHolder;

class StakeHolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StakeHolder::create(['name' => 'Maybank']);
		StakeHolder::create(['name' => 'DYSEKCO ENTERPRISES CORPORATION']);
		StakeHolder::create(['name' => '22NH0031-MEJVEC']);
    }
}
