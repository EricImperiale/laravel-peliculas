<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Con el método call() podemos pedir que se ejecute una clase de Seeder al
        // correr el comando de Artisan:
        //      php artisan db:seed
        $this->call(ClassificationSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(MovieSeeder::class);
        $this->call(UserSeeder::class);
    }
}
