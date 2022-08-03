<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZenviaJob extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'data'];
}
