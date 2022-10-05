<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Customer extends Model
{
    use AsSource, Filterable, HasFactory, SoftDeletes;

    protected $fillable         = ['external_code', 'user', 'cpf', 'name', 'email', 'birthdate', 'phone', 'city', 'state', 'defaulter'];

    protected $allowedSorts     = ['name', 'email', 'birthdate', 'city', 'updated_at'];

    protected $allowedFilters   = ['name', 'cpf', 'email', 'birthdate', 'city', 'updated_at'];

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function raffles()
    {
        return $this->hasMany(Raffle::class);
    }

    protected function birthdate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = date_create($value);
                return date_format($data, "d/m/Y");
            },
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $data = date_create($value);
                return date_format($data, "d/m/Y h:i");
            },
        );
    }

}
