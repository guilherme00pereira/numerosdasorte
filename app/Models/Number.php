<?php

namespace App\Models;

use App\Orchid\Presenters\NumberPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Number extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable = ['customer_id', 'category_id', 'order_id', 'number', 'expiration'];

    protected $allowedFilters = ['customer_id', 'updated_at'];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function category()
    {
        return $this->hasOne(RaffleCategory::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function raffle()
    {
        return $this->belongsTo(Raffle::class, "number");
    }

    public function presenter(): NumberPresenter
    {
        return new NumberPresenter($this);
    }
}
