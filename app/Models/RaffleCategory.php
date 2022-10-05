<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class RaffleCategory extends Model
{
    use AsSource, Filterable, HasFactory, SoftDeletes;

    protected $table = "raffle_categories";

    protected $fillable = ['name', 'repeatable'];
}
