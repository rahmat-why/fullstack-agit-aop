<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'deleted_at', 'updated_at'];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = now();
        });
    }
}