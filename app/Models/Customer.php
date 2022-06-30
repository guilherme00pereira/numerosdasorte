<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Customer extends Model
{
    use AsSource, HasFactory;

    protected $fillable = ['external_code','cpf','name','email','birthdate','phone','city','state','defaulter'];

}
