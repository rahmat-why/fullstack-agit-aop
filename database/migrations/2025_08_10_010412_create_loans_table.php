<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained('books')->onDelete('cascade');
            $table->foreignId('librarian_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('loan_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
