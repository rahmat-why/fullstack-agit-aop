<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Pemrograman', 'created_at' => now()],
            ['name' => 'Database', 'created_at' => now()],
            ['name' => 'Pengembangan Web', 'created_at' => now()],
        ]);
    }
}
