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
                'name' => 'Andi Prasetyo',
                'email' => 'andi.prasetyo@perpus.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'role' => 'librarian',
                'created_at' => now()
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@member.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'address' => 'Jl. Mawar No. 2, Bandung',
                'role' => 'member',
                'created_at' => now()
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra.dewi@member.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'address' => 'Jl. Melati No. 3, Surabaya',
                'role' => 'member',
                'created_at' => now()
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@perpus.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'address' => 'Jl. Anggrek No. 4, Yogyakarta',
                'role' => 'admin',
                'created_at' => now()
            ],
            [
                'name' => 'Eko Prabowo',
                'email' => 'eko.prabowo@perpus.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'address' => 'Jl. Kenanga No. 5, Medan',
                'role' => 'librarian',
                'created_at' => now()
            ]
        ]);
    }
}
