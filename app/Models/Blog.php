<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Blog extends Model
{
    use AsSource, HasFactory;

    protected $fillable = ['title', 'tag', 'content'];
}
