<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('book_categories')->insert([
            ['book_id' => 1, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 2, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 3, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 3, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 4, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 5, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 6, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 7, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 8, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 9, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 10, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['book_id' => 10, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
