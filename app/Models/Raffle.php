<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Raffle extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable         = ['raffle_date', 'prize', 'chosen_number','winner','category'];

    protected $allowedSorts     = ['raffle_date', 'chosen_number'];

    protected $allowedFilters   = ['raffle_date', 'chosen_number'];

    public function winner()
    {
        return $this->hasOne(Customer::class);
    }

    public function category()
    {
        return $this->hasOne(RaffleCategory::class);
    }

    protected $casts = [
        'raffle_date' => 'datetime'
    ];
}
