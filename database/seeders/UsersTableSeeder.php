<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Rahmat',
                'email' => 'rahmat@librarian.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'role' => 'librarian',
                'created_at' => now()
            ],
            [
                'name' => 'Yanto',
                'email' => 'yanto@member.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'address' => 'Jl. Mawar No. 2, Bandung',
                'role' => 'member',
                'created_at' => now()
            ]
        ]);
    }
}
