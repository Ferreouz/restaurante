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
        Schema::create('produtos_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('pedidos_id');
            
            $table->string('tipo');
            $table->text('opcao')->nullable();
            $table->text('guarnicoes')->nullable();
            $table->integer('quantidade')->default('1');
            $table->integer('valor');
           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos_pedidos');
    }
};
