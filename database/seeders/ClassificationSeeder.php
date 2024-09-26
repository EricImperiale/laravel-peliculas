<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classifications')->insert([
            [
                'classification_id' => 1,
                'name' => 'Apta Todo Público',
                'abbreviation' => 'ATP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classification_id' => 2,
                'name' => 'Mayores de 13 Años',
                'abbreviation' => 'M13',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classification_id' => 3,
                'name' => 'Mayores de 16 Años',
                'abbreviation' => 'M16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'classification_id' => 4,
                'name' => 'Mayores de 18 Años',
                'abbreviation' => 'M18',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
