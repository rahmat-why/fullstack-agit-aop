<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoansTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('loans')->insert([
            [
                'book_id' => 1,
                'librarian_id' => 1,
                'member_id' => 2,
                'loan_at' => '2025-08-09 10:00:00',
                'returned_at' => null,
                'note' => 'First loan record'
            ],
            [
                'book_id' => 3,
                'librarian_id' => 5,
                'member_id' => 3,
                'loan_at' => '2025-08-09 14:00:00',
                'returned_at' => '2025-08-10 09:00:00',
                'note' => 'Returned on time'
            ],
        ]);
    }
}