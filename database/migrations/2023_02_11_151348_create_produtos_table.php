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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->unsignedBigInteger('lista_id');
            $table->foreign('lista_id')->references('id')->on('lista_produtos')->onDelete('cascade');
            $table->string('tipo');
            $table->text('detalhes');
            $table->integer('valor');
            // $table->integer('id_pedido');
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
        Schema::dropIfExists('produtos');
    }
};
