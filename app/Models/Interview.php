<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Interview extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'video_id',
        'slug',
    ];

    protected $allowedSorts = [
        'title',
        'subtitle',
        'slug',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }
}
