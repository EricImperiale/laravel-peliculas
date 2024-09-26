<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            [
                'genre_id' => 1,
                'name' => 'Acción',
            ],
            [
                'genre_id' => 2,
                'name' => 'Aventuras',
            ],
            [
                'genre_id' => 3,
                'name' => 'Comedia',
            ],
            [
                'genre_id' => 4,
                'name' => 'Ciencia Ficción',
            ],
            [
                'genre_id' => 5,
                'name' => 'Fantasía',
            ],
            [
                'genre_id' => 6,
                'name' => 'Drama',
            ],
            [
                'genre_id' => 7,
                'name' => 'Romance',
            ],
            [
                'genre_id' => 8,
                'name' => 'Histórica',
            ],
        ]);
    }
}
