<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValoresCorretoresLancadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valores_corretores_lancados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date("data");
            $table->decimal("valor_comissao",10,2);
            $table->decimal("valor_salario",10,2);
            $table->decimal("valor_premiacao",10,2);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('valores_corretores_lancados');
    }
}
