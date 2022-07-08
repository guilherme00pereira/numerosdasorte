<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Raffle;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Blog extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable         = ['title', 'tag', 'raffle', 'content'];

    protected $allowedSorts     = ['title', 'raffle'];

    protected $allowedFilters   = ['title', 'raffle'];

    public function raffle()
    {
        return $this->hasOne(Raffle::class);
    }

}
