<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanceladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancelados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comissoes_id');
            $table->unsignedBigInteger('motivo_cancelados_id');
            $table->date('data_baixa');
            
            $table->string('observacao')->nullable();
            $table->foreign('comissoes_id')->references('id')->on('comissoes')->onDelete('cascade');
            $table->foreign('motivo_cancelados_id')->references('id')->on('motivo_cancelados')->onDelete('cascade');
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
        Schema::dropIfExists('cancelados');
    }
}
