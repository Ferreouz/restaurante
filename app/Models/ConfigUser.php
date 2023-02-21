<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfigUser extends Model
{
    use HasFactory;
    protected $table = 'config_users';
    public $timestamps = false;
    /**
    * @return void
    */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
