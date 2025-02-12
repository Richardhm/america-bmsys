<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComissoesCorretoraLancadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissoes_corretora_lancadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comissoes_id');
            $table->integer("parcela");
            $table->date("data");
            $table->date("data_baixa")->nullable();
            $table->decimal("valor",10,2);
            $table->boolean("status_financeiro")->nullable()->default(0);
            $table->boolean("status_gerente")->nullable()->default(0);
            $table->boolean('cancelados')->nullable()->default(0);           
            $table->foreign('comissoes_id')->references('id')->on('comissoes')->onDelete('cascade');
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
        Schema::dropIfExists('comissoes_corretora_lancadas');
    }
}
