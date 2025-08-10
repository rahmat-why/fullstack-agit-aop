<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'authors', 'isbn', 'description', 'deleted_at'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories');
    }
}
