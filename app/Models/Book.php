<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'image',
        'description',
    ];

    protected static function booted()
    {
        //add delete event
        static::deleting(function ($book) {
            //delete image
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
        });
    }
}
