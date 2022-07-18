<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Numbers extends Model
{
    use HasFactory;

    protected $fillable = ['customer', 'category', 'order', 'numbers', 'expiration'];

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
}
