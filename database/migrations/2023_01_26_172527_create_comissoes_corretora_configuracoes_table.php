<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComissoesCorretoraConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissoes_corretora_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plano_id');
            
            $table->unsignedBigInteger('administradora_id');
            $table->unsignedBigInteger('tabela_origens_id');
            
            $table->string('valor');
            $table->integer('parcela');
            $table->foreign('plano_id')->references('id')->on('planos')->onDelete("cascade");
            
            $table->foreign('administradora_id')->references('id')->on('administradoras')->onDelete("cascade");
            $table->foreign('tabela_origens_id')->references('id')->on('tabela_origens')->onDelete('cascade');
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
        Schema::dropIfExists('comissoes_corretora_configuracoes');
    }
}
