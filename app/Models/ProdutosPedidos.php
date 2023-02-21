<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdutosPedidos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'produtos_pedidos';
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
