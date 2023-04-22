<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Video extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'title',
        'url',
        'slug',
        'visibility',
        'published_at',
    ];

    protected $dates = [
        'published_at',
    ];
}
