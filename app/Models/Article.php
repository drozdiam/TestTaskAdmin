<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = true;

    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'image',
        'text',
        'active',
        'order',
    ];
}
