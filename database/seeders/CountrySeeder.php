<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            [
                'country_id' => 1,
                'name' => 'Argentina',
                'alpha3' => 'ARG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 2,
                'name' => 'Estados Unidos',
                'alpha3' => 'USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 3,
                'name' => 'Inglaterra',
                'alpha3' => 'ENG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
