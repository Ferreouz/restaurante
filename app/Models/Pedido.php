<?php

namespace App\Models;

use App\Models\ProdutosPedidos;
use App\Models\UsuariosPedidos;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedidos';
    // public $timestamps = false;

    public function usuarios_pedidos(){
        return $this->belongsTo(UsuariosPedidos::class,'usuarios_pedidos_id');
    }
    public function produtos_pedidos(){
        return $this->hasMany(ProdutosPedidos::class,'pedidos_id');
    }

     /**
    * @return void
    */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
