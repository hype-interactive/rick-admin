<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Lyrics extends Model
{
    use HasFactory, AsSource;

    protected $table = 'lyrics';

    protected $fillable = [
        'image',
        'cover_image',
        'song',
        'content',
        'artist_id',
        'album',
        'audio_link',
        'video_link',
        'visibility',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id', 'id');
    }
}
