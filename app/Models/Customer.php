<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Customer extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable         = ['external_code', 'user', 'cpf','name','email','birthdate','phone','city','state','defaulter'];

    protected $allowedSorts     = ['name','email','birthdate','city','updated_at'];

    protected $allowedFilters   = ['name','email','birthdate','city','updated_at'];

}
