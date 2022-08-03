<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscardedNumber extends Model
{
    use HasFactory;

    protected $fillable = [ 'raffle_id', 'number_id' ];

    public function raffle()
    {
        return $this->hasMany(Raffle::class);
    }

    public function number()
    {
        return $this->hasMany(Number::class);
    }
}
