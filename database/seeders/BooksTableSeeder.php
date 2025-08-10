<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            ['title' => 'Dasar-Dasar MySQL', 'description' => 'Panduan pemula untuk memahami database MySQL.', 'authors' => 'Joko Widodo', 'isbn' => '978-1234567890', 'created_at' => now()],
            ['title' => 'Pemrograman Java Lengkap', 'description' => 'Buku lengkap pemrograman Java untuk pemula hingga mahir.', 'authors' => 'Agus Setiawan', 'isbn' => '978-0987654321', 'created_at' => now()],
            ['title' => 'Belajar PHP untuk Web', 'description' => 'Langkah demi langkah belajar PHP.', 'authors' => 'Ahmad Fadli', 'isbn' => '978-1122334455', 'created_at' => now()],
            ['title' => 'HTML & CSS Tingkat Lanjut', 'description' => 'Panduan desain web modern.', 'authors' => 'Siti Aminah', 'isbn' => '978-6677889900', 'created_at' => now()],
            ['title' => 'Struktur Data dengan C++', 'description' => 'Membahas struktur data secara detail.', 'authors' => 'Budi Santoso', 'isbn' => '978-5566778899', 'created_at' => now()],
            ['title' => 'Jaringan Komputer Dasar', 'description' => 'Konsep jaringan komputer untuk pemula.', 'authors' => 'Rahmat Hidayat', 'isbn' => '978-9988776655', 'created_at' => now()],
            ['title' => 'Python untuk Data Science', 'description' => 'Analisis data dengan Python.', 'authors' => 'Dian Saputra', 'isbn' => '978-8877665544', 'created_at' => now()],
            ['title' => 'Algoritma dan Pemrograman', 'description' => 'Konsep algoritma dasar untuk semua bahasa.', 'authors' => 'Nur Aini', 'isbn' => '978-7766554433', 'created_at' => now()],
            ['title' => 'Keamanan Siber', 'description' => 'Panduan keamanan siber untuk organisasi.', 'authors' => 'Ridwan Kamil', 'isbn' => '978-6655443322', 'created_at' => now()],
            ['title' => 'Laravel Framework', 'description' => 'Membangun web modern dengan Laravel.', 'authors' => 'Yusuf Maulana', 'isbn' => '978-5544332211', 'created_at' => now()],
        ]);
    }
}
