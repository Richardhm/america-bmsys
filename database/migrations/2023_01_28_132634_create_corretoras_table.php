<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorretorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corretoras', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string("logo")->nullable();
            $table->string("endereco")->nullable();
            $table->string("telefone")->nullable();
            $table->string("site")->nullable();
            $table->string('email')->nullable();
            $table->string('instagram')->nullable();            
            $table->decimal('consultas_eletivas',10,2)->nullable();
            $table->decimal('consultas_urgencia',10,2)->nullable();
            $table->decimal('exames_simples',10,2)->nullable();
            $table->decimal('exames_complexos',10,2)->nullable();
            $table->string("linha_01_coletivo")->nullable();
            $table->string("linha_02_coletivo")->nullable();
            $table->string("linha_03_coletivo")->nullable();
            $table->string("linha_01_individual")->nullable();
            $table->string("linha_02_individual")->nullable();
            $table->string("linha_03_individual")->nullable();
            $table->string("cor")->nullable();
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
        Schema::dropIfExists('corretoras');
    }
}
