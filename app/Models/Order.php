<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use AsSource;

    protected $fillable = ['order_id', 'value', 'installments', 'payment_type', 'num_items', 'customer_id'];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
