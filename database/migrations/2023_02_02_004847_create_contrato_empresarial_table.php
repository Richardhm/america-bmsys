<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoEmpresarialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_empresarial', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('plano_empresarial_id')->nullable();
            $table->unsignedBigInteger('tabela_origens_id');
            $table->unsignedBigInteger('financeiro_id');
            $table->unsignedBigInteger('user_id');
            $table->date('data');

            $table->string('codigo_corretora');
            $table->string('codigo_vendedor');
            $table->string('cnpj');
            $table->string('razao_social');
            $table->integer('quantidade_vidas');

            $table->decimal('taxa_adesao',10,2);
            $table->decimal('valor_plano',10,2);
            $table->decimal('valor_total',10,2);

            $table->decimal('desconto_corretora',10,2)->nullable();
            $table->decimal('desconto_corretor',10,2)->nullable();



            $table->date('vencimento_boleto');
            $table->decimal('valor_boleto',10,2);            
            $table->string('codigo_cliente');
            $table->string('senha_cliente');
            $table->decimal('valor_plano_saude',10,2);    
            $table->decimal('valor_plano_odonto',10,2);    

            $table->string('codigo_saude');
            $table->string('codigo_odonto');
            $table->string('codigo_externo');


            $table->date('data_boleto');

            $table->string('responsavel');
            $table->string('telefone');
            $table->string('celular');
            $table->string('email');
            $table->string('cidade');
            $table->string('uf');
            
            $table->integer('plano_contrado');

            $table->foreign('plano_empresarial_id')->references('id')->on('plano_empresarial');
            $table->foreign('tabela_origens_id')->references('id')->on('tabela_origens');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('contrato_empresarial');
    }
}
