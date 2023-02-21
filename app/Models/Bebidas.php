<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bebidas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "lista_bebidas";

    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }

}
