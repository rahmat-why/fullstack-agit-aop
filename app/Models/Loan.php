<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['librarian_id', 'member_id', 'book_id', 'loan_at', 'returned_at', 'note'];

    public function librarian() {
        return $this->belongsTo(User::class, 'librarian_id');
    }

    public function member() {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function book() {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public $timestamps = false;
}
