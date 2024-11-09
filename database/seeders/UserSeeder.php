<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'user_id' => 1,
                'email' => 'ericimperiale@hotmail.com',
                'password' => Hash::make('123'),
                'isAdmin' => true,
                'provider_id' => null,
                'provider_token' => null,
                'provider_refresh_token' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'email' => 'user@hotmail.com',
                'password' => Hash::make('123'),
                'isAdmin' => false,
                'provider_id' => null,
                'provider_token' => null,
                'provider_refresh_token' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
