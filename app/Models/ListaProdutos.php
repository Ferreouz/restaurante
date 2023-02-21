<?php

namespace App\Models;

use App\Models\Produtos;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListaProdutos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "lista_produtos";

    public function produtos(){
        return $this->hasMany(Produtos::class, 'lista_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
