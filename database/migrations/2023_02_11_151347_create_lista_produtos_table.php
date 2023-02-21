<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            
            $table->string('nome');
            $table->string('imagem')->default('');
            $table->boolean('ativado_hoje');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_produtos');
    }
};
