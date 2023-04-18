<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Advert extends Model
{
    use HasFactory, AsSource;

    protected $table = 'adverts';

    protected $fillable = [
        'title',
        'description',
        'type',
        'link',
        'price',
        'image',
    ];
}
