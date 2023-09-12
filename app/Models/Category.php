<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'active',
        'order',
    ];
    public function Article()
    {
        $this->hasMany(Article::class);
    }
}
