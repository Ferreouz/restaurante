<?php

namespace App\Models;

use App\Models\ListaProdutos;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produtos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "produtos";

    public function lista(){
        return $this->belongsTo(ListaProdutos::class, 'lista_id');
    }
      
    // /**
    //  * Example usage:
    //  * Post::owner()->get();
    //  *
    //  * @param \Illuminate\Database\Eloquent\Builder $query
    //  * @return \Illuminate\Database\Eloquent\Builder
    //  */
    // public function scopeOwner($query){
    //     return $query->where([
    //         'user_id' => auth()->user()->id,
    //     ]);
    // }//end scopeOwner()

    /**
    * @return void
    */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
    
}
