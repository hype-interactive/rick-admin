<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Article extends Model
{
    use HasFactory, AsSource;

    protected $dates = [
        'published_at',
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'user_id',
        'published_at',
        'content',
        'image',
        'slug',
        'category_id',
        'visibility',
        'pin',
    ];

    protected $allowedSorts = [
        'title',
        'subtitle',
        'user_id',
        'published_at',
        'content',
        'slug',
        'category_id',
        'visibility',
        'pin',
    ];

    protected $allowedFilters = [
        'title',
        'subtitle',
        'user_id',
        'published_at',
        'content',
        'slug',
        'category_id',
        'visibility',
        'pin',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(ArticleTag::class);
    }
}
