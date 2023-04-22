<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Illuminate\Support\Str;

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

    public function setSlugAttribute($value)
    {
        $titleWords = explode(' ', $this->attributes['title']);
        $words = array_filter($titleWords, function ($word) {
            return strlen($word) >= 3;
        });

        $words = array_slice($words, 0,6);

        $rst_str = array_merge($words,[rand(1,1000),"rickmedia"]);

        $slug = Str::slug(implode(' ', $rst_str ), '-');
        $count = 0;
        $originalSlug = $slug;

        while (static::whereSlug($slug)->where('id', '<>', $this->id)->exists()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }

        $this->attributes['slug'] = $slug;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->setSlugAttribute($value);
    }


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
