<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuariosPedidos extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $table = 'usuarios_pedidos';
    protected $fillable = [
        'user_id',
        'nome_completo',
        'nome',
        'endereco',
        'whatsapp',
        'id_bot',
        'cpf',
        'nascimento'
    ];
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
    
}
