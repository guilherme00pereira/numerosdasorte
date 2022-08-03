<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Raffle extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable         = ['raffle_date', 'prize', 'lottery_number', 'number','customer','category'];

    protected $allowedSorts     = ['raffle_date', 'number'];

    protected $allowedFilters   = ['raffle_date', 'number'];

    public function customer()
    {
        return $this->hasOne(Customer::class );
    }

    public function category()
    {
        return $this->hasOne(RaffleCategory::class);
    }

    public function number()
    {
        return $this->hasOne(Number::class);
    }

    protected $casts = [
        'raffle_date' => 'datetime'
    ];
}
