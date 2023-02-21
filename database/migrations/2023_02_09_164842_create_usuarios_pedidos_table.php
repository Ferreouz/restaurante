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
        Schema::create('usuarios_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            
            $table->integer('id_bot')->nullable();
            $table->string('whatsapp')->nullable();   
            $table->text('nome_completo')->nullable();
            $table->text('nome')->nullable();
            $table->text('endereco')->nullable();
            $table->string('cpf')->nullable();
            $table->date('nascimento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_pedidos');
    }
};
