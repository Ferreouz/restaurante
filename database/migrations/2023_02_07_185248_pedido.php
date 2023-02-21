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
        
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuarios_pedidos_id');
            $table->foreignId('user_id');
            
            $table->text('endereco')->nullable();//maybe
            $table->string('retirar_entregar')->default('Entrega');

            $table->integer('troco')->default('0');
            $table->integer('frete');
            $table->integer('valor_total');
            $table->string('forma_pagamento')->default('Nao especificado');
            
            $table->text('observacao')->nullable();

            $table->integer('situacao')->nullable();
            $table->integer('situacaoImpressao')->nullable();

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
        Schema::dropIfExists('pedidos');
    }
};
