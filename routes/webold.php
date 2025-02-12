<?php

use App\Models\Comissoes;
use App\Models\Contrato;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');


Route::middleware('auth')->prefix("admin")->group(function(){


    Route::get("/reposicionar",function(){

        $comissoes = \Illuminate\Support\Facades\DB::select("
              select
                  *
              from comissoes_corretores_lancadas where
              comissoes_id IN(select id from comissoes where contrato_id IN(select id from contratos where plano_id = 1 and financeiro_id != 12))
              and status_financeiro = 1
        ");
        foreach($comissoes as $cc) {

            switch ($cc->parcela) {
                case 2:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update([
                        "financeiro_id" => 6
                    ]);

                    break;

                case 3:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update([
                        "financeiro_id" => 7
                    ]);
                    break;

                case 4:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update([
                        "financeiro_id" => 8
                    ]);
                    break;

                case 5:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update([
                        "financeiro_id" => 9
                    ]);
                    break;

                case 6:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update([
                        "financeiro_id" => 10
                    ]);
                    break;

                default:
                    $contrato_id = Comissoes::where("id", $cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id", $contrato_id)->update([
                        "financeiro_id" => 5
                    ]);
                    break;
            }
        }
    });








    /* Home **/
    Route::get("/","App\Http\Controllers\Admin\HomeController@index");
    Route::get("/tabela_preco","App\Http\Controllers\Admin\HomeController@search")->name('orcamento.search.home');

    Route::post("/tabela_preco","App\Http\Controllers\Admin\HomeController@tabelaPrecoResposta")->name('tabela.preco.resposta');
    Route::post("/tabela_preco/cidade/resposta","App\Http\Controllers\Admin\HomeController@tabelaPrecoRespostaCidade")->name('tabela.preco.resposta.cidade');

    Route::get("/consultar","App\Http\Controllers\Admin\HomeController@consultar")->name('home.administrador.consultar');
    Route::post("/consultar","App\Http\Controllers\Admin\HomeController@consultarCarteirnha")->name('consultar.carteirinha');
    /* Fim Home**/

    /**Orçamentos  */    
    Route::get('/orcamento',"App\Http\Controllers\Admin\OrcamentoController@index")->name('orcamento.index');
    Route::post("/orcamento","App\Http\Controllers\Admin\OrcamentoController@montarOrcamento")->name('orcamento.montarOrcamento');
    Route::post("/orcamento/administradoras","App\Http\Controllers\Admin\OrcamentoController@montarOrcamentoAdministradoras")->name('orcamento.administradoras.montar');



    Route::post("/orcamento/criarpdf","App\Http\Controllers\Admin\OrcamentoController@criarPDF")->name('orcamento.criarpdf');
    Route::post("/orcamento/criarpdf/ambultorial","App\Http\Controllers\Admin\OrcamentoController@criarPDFAmbulatorial")->name('orcamento.criarpdfambulatorial');  
    Route::post("/orcamento/criarpdf/empresarial","App\Http\Controllers\Admin\OrcamentoController@criarPDFEmpresarial")->name('orcamento.criarpdfempresarial');



    /**Fim Orçamentos */

    /**Contratos*/
    Route::get('/contratos',"App\Http\Controllers\Admin\ContratoController@index")->name('contratos.index');
    Route::post('/contratos',"App\Http\Controllers\Admin\ContratoController@store")->name('contratos.store');
    Route::post('/contratos/individual',"App\Http\Controllers\Admin\ContratoController@storeIndividual")->name('individual.store');
    Route::post('/contratos/montarPlanos',"App\Http\Controllers\Admin\ContratoController@montarPlanos")->name('contratos.montarPlanos');
    Route::post('/contratos/montarPlanosIndividual',"App\Http\Controllers\Admin\ContratoController@montarPlanosIndividual")->name('contratos.montarPlanosIndividual');
    Route::post('/contratos/empresarial',"App\Http\Controllers\Admin\ContratoController@storeEmpresarial")->name('contratos.storeEmpresarial');  
    Route::get('/contratos/listarColetivoPorAdesao',"App\Http\Controllers\Admin\ContratoController@listarColetivoPorAdesao")->name('contratos.listarColetivoPorAdesao');
    //Route::get('/contratos/listarindividual',"App\Http\Controllers\Admin\ContratoController@listarIndividual")->name('contratos.listarIndividual');
    //Route::get('/contratos/listarempresas',"App\Http\Controllers\Admin\ContratoController@listarContratoEmpresarial")->name('contratos.listarEmpresarial');
    Route::get("/contratos/individual/em_geral_contrato","App\Http\Controllers\Admin\ContratoController@geralIndividualPendentes")->name('financeiro.individual.geralIndividualPendentes.contrato');
    Route::get("/contrato/individual/em_geral_contrato","App\Http\Controllers\Admin\ContratoController@geralIndividualPendentesCorretor")->name('financeiro.individual.geralIndividualPendentes.contrato.corretor');


    
    Route::get("/contratos/coletivo/em_geral_contrato","App\Http\Controllers\Admin\ContratoController@geralColetivoPendentes")->name('financeiro.individual.geralColetivoPendentes.contrato');
    Route::get("/contrato/coletivo/em_geral_contrato","App\Http\Controllers\Admin\ContratoController@geralColetivoPendentesCorretor")->name('financeiro.individual.geralColetivoPendentes.contrato.corretor');


    Route::get('/contratos/listarempresas/emgeral',"App\Http\Controllers\Admin\ContratoController@listarEmpresarialEmGeral")->name('contratos.listarEmpresarial.emgeral');
    Route::get('/contratos/listarempresas/emanalise',"App\Http\Controllers\Admin\ContratoController@listarContratoEmAnalise")->name('contratos.listarEmpresarial.analise');
    Route::get('/contratos/empendentes/empresarial',"App\Http\Controllers\Admin\ContratoController@listarContratoEmpresaPendentes")->name('contratos.listarEmpresarial.listarContratoEmpresaPendentes');
    Route::get('/contratos/listarempresas/aguardando_primeiro_parcela',"App\Http\Controllers\Admin\ContratoController@listarContratoPrimeiraParcela")->name('contratos.listarEmpresarial.primeiraparcela');
    Route::get('/contratos/listarempresas/aguardando_segunda_parcela',"App\Http\Controllers\Admin\ContratoController@listarContratoSegundaParcela")->name('contratos.listarEmpresarial.segundaparcela');
    Route::get('/contratos/listarempresas/aguardando_terceira_parcela',"App\Http\Controllers\Admin\ContratoController@listarContratoTerceiraParcela")->name('contratos.listarEmpresarial.terceiraparcela');
    Route::get('/contratos/listarempresas/aguardando_quarta_parcela',"App\Http\Controllers\Admin\ContratoController@listarContratoQuartaParcela")->name('contratos.listarEmpresarial.quartaparcela');
    Route::get('/contratos/listarempresas/aguardando_quinta_parcela',"App\Http\Controllers\Admin\ContratoController@listarContratoQuintaParcela")->name('contratos.listarEmpresarial.quintaparcela');
    Route::get('/contratos/listarempresas/aguardando_sexta_parcela',"App\Http\Controllers\Admin\ContratoController@listarContratoSextaParcela")->name('contratos.listarEmpresarial.sextaparcela');
    Route::get('/contratos/listarempresas/finalizado',"App\Http\Controllers\Admin\ContratoController@listarContratoEmpresarialFinalizado")->name('contratos.listarEmpresarial.finalizado');
    Route::get('/contratos/listarempresas/cancelado',"App\Http\Controllers\Admin\ContratoController@listarContratoEmpresarialCancelado")->name('contratos.listarEmpresarial.cancelado');
    Route::post('/contratos/pegarEmpresarialPorUser',"App\Http\Controllers\Admin\ContratoController@listarEmpresarialPorUser")->name('contratos.listarEmpresarialPorUser');
    Route::post('/contratos/descricao',"App\Http\Controllers\Admin\ContratoController@contratoInfo")->name('contratos.info');
    Route::get('/contratos/cadastrar/individual',"App\Http\Controllers\Admin\ContratoController@formCreate")->name('contratos.create');
    Route::get('/contrato/cadastrar/individual',"App\Http\Controllers\Admin\ContratoController@formContratoCreate")->name('contrato.create');

    Route::get('/financeiro/detalhes/empresarial/{id}',"App\Http\Controllers\Admin\FinanceiroController@detalheEmpresarial")->name('financeiro.empresarial.detalhe');

    Route::get('/contratos/cadastrar/coletivo',"App\Http\Controllers\Admin\ContratoController@formCreateColetivo")->name('contratos.create.coletivo');
    Route::get('/contrato/cadastrar/coletivo',"App\Http\Controllers\Admin\ContratoController@formCreateColetivoCorretor")->name('contratos.create.coletivo.corretor');

    Route::post('/financeiro/importarDados',"App\Http\Controllers\Admin\FinanceiroController@importarDados")->name('financeiro.importar.dados');

    Route::get('/financeiro/cliente/semcarteirinha',"App\Http\Controllers\Admin\FinanceiroController@semCarteirinha")->name('financeiro.sem.carteirinha');

    Route::post('/financeiro/cliente/atualizar/carteirinha',"App\Http\Controllers\Admin\FinanceiroController@atualizarCarteirinha")->name('cliente.atualizar.carteirinha');

    Route::get('/financeiro/cancelado/detalhes/{id}',"App\Http\Controllers\Admin\FinanceiroController@clienteCancelado")->name('cliente.cancelado.detalhes');



    Route::get('/contratos/cadastrar/empresarial',"App\Http\Controllers\Admin\ContratoController@formCreateEmpresarial")->name('contratos.create.empresarial');
    Route::post('/contratos/empresarial/financeiro',"App\Http\Controllers\Admin\FinanceiroController@storeEmpresarialFinanceiro")->name('contratos.storeEmpresarial.financeiro');  

    Route::get('/contrato',"App\Http\Controllers\Admin\ContratoController@contrato")->name('contrato.index');





    /**Fim Contratos*/

    /**Financeiro*/
    Route::get('/financeiro',"App\Http\Controllers\Admin\FinanceiroController@index")->name('financeiro.index');
    Route::get('/financeiro/coletivo/em_analise',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmAnalise")->name('financeiro.coletivo.em_analise');
    Route::get('/financeiro/coletivo/em_analise/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmAnaliseCorretor")->name('financeiro.coletivo.em_analise.corretor');


    Route::get('/financeiro/coletivo/em_branco',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmBranco")->name('financeiro.coletivo.em_branco');
    Route::get('/financeiro/coletivo/em_geral',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmGeral")->name('financeiro.coletivo.em_geral');
    Route::get('/financeiro/coletivo/em_geral/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmGeralCorretor")->name('financeiro.coletivo.em_geral.corretor');

    Route::get('/financeiro/empresarial/em_geral',"App\Http\Controllers\Admin\FinanceiroController@empresarialEmGeral")->name('financeiro.empresarial.em_geral');

    Route::get('/financeiro/coletivo/emissao_boleto',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmissaoBoleto")->name('financeiro.coletivo.emissao_boleto');
    Route::get('/financeiro/coletivo/emissao_boleto/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoEmissaoBoletoCorretor")->name('financeiro.coletivo.emissao_boleto.corretor');
    
       
    Route::get('/financeiro/coletivo/pagamento_adesao',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoAdesao")->name('financeiro.coletivo.pagamento_adesao');
    Route::get('/financeiro/coletivo/pagamento_adesao/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoAdesaoCorretor")->name('financeiro.coletivo.pagamento_adesao.corretor');
        
    Route::get('/financeiro/coletivo/pagamento_vigencia',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoVigencia")->name('financeiro.coletivo.pagamento_vigencia');
    Route::get('/financeiro/coletivo/pagamento_vigencia/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoVigenciaCorretor")->name('financeiro.coletivo.pagamento_vigencia.corretor');
        
    Route::get('/financeiro/coletivo/pagamento_segunda_parcela',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoSegundaParcela")->name('financeiro.coletivo.pagamento_segunda_parcela');
    Route::get('/financeiro/coletivo/pagamento_segunda_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoSegundaParcelaCorretor")->name('financeiro.coletivo.pagamento_segunda_parcela.corretor');
    
    Route::get('/financeiro/coletivo/pagamento_terceira_parcela',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoTerceiraParcela")->name('financeiro.coletivo.pagamento_terceira_parcela');
    Route::get('/financeiro/coletivo/pagamento_terceira_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoTerceiraParcelaCorretor")->name('financeiro.coletivo.pagamento_terceira_parcela.corretor');
        
    Route::get('/financeiro/coletivo/pagamento_quarta_parcela',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoQuartaParcela")->name('financeiro.coletivo.pagamento_quarta_parcela');
    Route::get('/financeiro/coletivo/pagamento_quarta_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoQuartaParcelaCorretor")->name('financeiro.coletivo.pagamento_quarta_parcela.corretor');
    
    Route::get('/financeiro/coletivo/pagamento_quinta_parcela',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoQuintaParcela")->name('financeiro.coletivo.pagamento_quinta_parcela');
    Route::get('/financeiro/coletivo/pagamento_quinta_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoQuintaParcelaCorretor")->name('financeiro.coletivo.pagamento_quinta_parcela.corretor');
    
    Route::get('/financeiro/coletivo/pagamento_sexta_parcela',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoSextaParcela")->name('financeiro.coletivo.pagamento_sexta_parcela');
    Route::get('/financeiro/coletivo/pagamento_sexta_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoPagamentoSextaParcelaCorretor")->name('financeiro.coletivo.pagamento_sexta_parcela.corretor');
    
    Route::get('/financeiro/coletivo/pagamento_coletivo_finalizado',"App\Http\Controllers\Admin\FinanceiroController@coletivoFinalizado")->name('financeiro.coletivo.finalizado');
    Route::get('/financeiro/coletivo/pagamento_coletivo_finalizado/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoFinalizadoCorretor")->name('financeiro.coletivo.finalizado.corretor');

    Route::get('/financeiro/coletivo/pagamento_coletivo_cancelado',"App\Http\Controllers\Admin\FinanceiroController@coletivoCancelados")->name('financeiro.coletivo.cancelado');
    Route::get('/financeiro/coletivo/pagamento_coletivo_cancelado/corretor',"App\Http\Controllers\Admin\FinanceiroController@coletivoCanceladosCorretor")->name('financeiro.coletivo.cancelado.corretor');    

    
    Route::get("/financeiro/todososcontratos/em_geral_todos_os_planos","App\Http\Controllers\Admin\FinanceiroController@geralTodosContratosPendentes")->name('financeiro.todos.geralTodosContratosPendentes');
    Route::get("/financeiro/individual/em_geral","App\Http\Controllers\Admin\FinanceiroController@geralIndividualPendentes")->name('financeiro.individual.geralIndividualPendentes');

    Route::get("/financeiro/individual/mudar_ano/{ano}/{mes?}","App\Http\Controllers\Admin\FinanceiroController@mudarAnoIndividual")->name('financeiro.individual.mudarano');
    Route::get("/financeiro/individual/mudar_mes/{mes}/{ano?}","App\Http\Controllers\Admin\FinanceiroController@mudarMesIndividual")->name('financeiro.individual.mudarmes');

    Route::get("/financeiro/coletivo/mudar_ano/{ano}/{mes?}","App\Http\Controllers\Admin\FinanceiroController@mudarAnoColetivo")->name('financeiro.coletivo.mudarano');
    Route::get("/financeiro/coletivo/mudar_mes/{mes}/{ano?}","App\Http\Controllers\Admin\FinanceiroController@mudarMesColetivo")->name('financeiro.coletivo.mudarmes');

    
    Route::get('/financeiro/individual/em_analise',"App\Http\Controllers\Admin\FinanceiroController@emAnaliseIndividual")->name('financeiro.individual.em_analise');
    Route::get('/financeiro/individual/em_analise/corretor',"App\Http\Controllers\Admin\FinanceiroController@emAnaliseIndividualCorretor")->name('financeiro.individual.em_analise.corretor');


    Route::post('/financeiro/sincronizar',"App\Http\Controllers\Admin\FinanceiroController@sincronizarDados")->name('financeiro.sincronizar');
    Route::post('/financeiro/sincronizar/coletivo',"App\Http\Controllers\Admin\FinanceiroController@sincronizarDadosColetivo")->name('financeiro.sincronizar.coletivo');
    Route::post('/financeiro/atualizar_dados',"App\Http\Controllers\Admin\FinanceiroController@atualizarDados")->name('financeiro.atualizar.dados');
    Route::post('/financeiro/sincronizar_baixas',"App\Http\Controllers\Admin\FinanceiroController@sincronizarBaixas")->name('financeiro.sincronizar.baixas');

    Route::post('/financeiro/sincronizar_baixas/ja_existente',"App\Http\Controllers\Admin\FinanceiroController@sincronizarBaixasJaExiste")->name('financeiro.sincronizar.baixas.jaexiste');



    Route::get('/financeiro/detalhes/{id}',"App\Http\Controllers\Admin\FinanceiroController@detalhesContrato")->name('financeiro.detalhes.contrato');
    Route::get('/financeiro/detalhes/coletivo/{id}',"App\Http\Controllers\Admin\FinanceiroController@detalhesContratoColetivo")->name('financeiro.detalhes.contrato.coletivo');

    Route::post('/financeiro/changeclienteuser',"App\Http\Controllers\Admin\FinanceiroController@changeclienteuser")->name('change.cliente.user');
    Route::post('/financeiro/verificardependentes','App\Http\Controllers\Admin\FinanceiroController@verificardependentesuser')->name('verificar.dependentes.user');

    Route::get('/financeiro/individual/pagamento_primeira_parcela',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoPrimeiraParcela")->name('financeiro.individual.pagamento_primeira_parcela');
    Route::get('/financeiro/individual/pagamento_primeira_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoPrimeiraParcelaCorretor")->name('financeiro.individual.pagamento_primeira_parcela.corretor');


    Route::get('/financeiro/individual/pagamento_segunda_parcela',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoSegundaParcela")->name('financeiro.individual.pagamento_segunda_parcela');
    Route::get('/financeiro/individual/pagamento_segunda_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoSegundaParcelaCorretor")->name('financeiro.individual.pagamento_segunda_parcela.corretor');
    
    Route::get('/financeiro/individual/pagamento_terceira_parcela',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoTerceiraParcela")->name('financeiro.individual.pagamento_terceira_parcela');
    Route::get('/financeiro/individual/pagamento_terceira_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoTerceiraParcelaCorretor")->name('financeiro.individual.pagamento_terceira_parcela.corretor');
    
    Route::get('/financeiro/individual/pagamento_quarta_parcela',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoQuartaParcela")->name('financeiro.individual.pagamento_quarta_parcela');
    Route::get('/financeiro/individual/pagamento_quarta_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoQuartaParcelaCorretor")->name('financeiro.individual.pagamento_quarta_parcela.corretor');
    
    Route::get('/financeiro/individual/pagamento_quinta_parcela',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoQuintaParcela")->name('financeiro.individual.pagamento_quinta_parcela');
    Route::get('/financeiro/individual/pagamento_quinta_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoQuintaParcelaCorretor")->name('financeiro.individual.pagamento_quinta_parcela.corretor');
    
    Route::get('/financeiro/individual/pagamento_sexta_parcela',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoSextaParcela")->name('financeiro.individual.pagamento_sexta_parcela');   
    Route::get('/financeiro/individual/pagamento_sexta_parcela/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualPagamentoSextaParcelaCorretor")->name('financeiro.individual.pagamento_sexta_parcela.corretor');   
    
    
    Route::get('/financeiro/individual/finalizado',"App\Http\Controllers\Admin\FinanceiroController@individualFinalizado")->name('financeiro.individual.finalizado');
    Route::get('/financeiro/individual/finalizado/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualFinalizadoCorretor")->name('financeiro.individual.finalizado.corretor');

    Route::get('/financeiro/coletivo/pagamento_individual_cancelado',"App\Http\Controllers\Admin\FinanceiroController@individualCancelados")->name('financeiro.individual.cancelado');
    Route::get('/financeiro/coletivo/pagamento_individual_cancelado/corretor',"App\Http\Controllers\Admin\FinanceiroController@individualCanceladosCorretor")->name('financeiro.individual.cancelado.corretor');
    
    
    Route::post('/financeiro/mudarEstadosColetivo',"App\Http\Controllers\Admin\FinanceiroController@mudarEstadosColetivo")->name('financeiro.mudarStatusColetivo');
    Route::post('/financeiro/mudarEstadosIndividual',"App\Http\Controllers\Admin\FinanceiroController@mudarEstadosIndividual")->name('financeiro.mudarStatusIndividual');
    Route::post('/financeiro/mudarEstadosEmpresarial',"App\Http\Controllers\Admin\FinanceiroController@mudarEstadosEmpresarial")->name('financeiro.mudarStatusEmpresarial');

    Route::post('/financeiro/mudarEstadosEmpresarialDescontos',"App\Http\Controllers\Admin\FinanceiroController@mudarEstadosEmpresarialDescontos")->name('financeiro.mudarStatusEmpresarialUpdate');

    Route::post("/financeiro/mudarDataVigenciaColetivo","App\Http\Controllers\Admin\FinanceiroController@mudarDataVivenciaColetivo")->name('financeiro.mudarVigenciaColetivo');
    Route::post('/financeiro/baixaDaData',"App\Http\Controllers\Admin\FinanceiroController@baixaDaData")->name('financeiro.baixa.data');
    Route::post('/financeiro/baixaDaData/individual',"App\Http\Controllers\Admin\FinanceiroController@baixaDaDataIndividual")->name('financeiro.baixa.data.individual');
    Route::post('/financeiro/baixaDaData/empresarial',"App\Http\Controllers\Admin\FinanceiroController@baixaDaDataEmpresarial")->name('financeiro.baixa.data.empresarial');
    Route::post('/financeiro/editarCampoIndividualmente',"App\Http\Controllers\Admin\FinanceiroController@editarCampoIndividualmente")->name('financeiro.editar.campoIndividualmente');
    Route::post('/financeiro/editarIndividualCampoIndividualmente',"App\Http\Controllers\Admin\FinanceiroController@editarIndividualCampoIndividualmente")->name('financeiro.editar.individual.campoIndividualmente');
    Route::post('/financeiro/editarCampoEmpresarial/campo',"App\Http\Controllers\Admin\FinanceiroController@editarCampoEmpresarialIndividual")->name('financeiro.editar.empresarial.campoIndividualmente');



    Route::post('/financeiro/contratos',"App\Http\Controllers\Admin\FinanceiroController@verContrato")->name('financeiro.ver.contrato');
    Route::post('/financeiro/cancelados',"App\Http\Controllers\Admin\FinanceiroController@cancelarContrato")->name('financeiro.contrato.cancelados');
    Route::post('/financeiro/excluir',"App\Http\Controllers\Admin\FinanceiroController@excluirCliente")->name('financeiro.excluir.cliente');
    Route::post('/financeiro/excluir/individual',"App\Http\Controllers\Admin\FinanceiroController@excluirClienteIndividual")->name('financeiro.excluir.cliente.individual');

    Route::post('/financeiro/excluir/empresarial',"App\Http\Controllers\Admin\FinanceiroController@excluirClienteEmpresarial")->name('financeiro.excluir.cliente.empresarial');

    Route::post('/financeiro/quantidade/corretor',"App\Http\Controllers\Admin\FinanceiroController@quantidadeCorretor")->name('financeiro.corretor.quantidade');

    Route::get('/financeiro/geral/atrsado',"App\Http\Controllers\Admin\FinanceiroController@getAtrasados")->name('financeiro.individual.atrasado');
    Route::get('/financeiro/geral/atrasado/corretor',"App\Http\Controllers\Admin\FinanceiroController@getAtrasadosCorretor")->name('financeiro.individual.atrasado.corretor');

    /**Fim Financeiro*/

    /***Comissões*****/
    Route::get('/comissao',"App\Http\Controllers\Admin\ComissoesController@index")->name('comissao.index');
    Route::get('/comissao/listarindividual',"App\Http\Controllers\Admin\ComissoesController@listarComissoes")->name('comissao.listar');
    /***Fim Comissões*****/

    /***Premiação***/
    Route::get('/premiacao',"App\Http\Controllers\Admin\PremiacaoController@index")->name('premiacao.index');
    Route::get('/premiacao/listarindividual',"App\Http\Controllers\Admin\PremiacaoController@listarPremiacao")->name('premiacao.listar');
    /***Fim Premiação***/

    Route::get("/profile/{id}","App\Http\Controllers\Admin\UserController@getUser")->name("profile.getUser");
    Route::post("/profile","App\Http\Controllers\Admin\UserController@setUser")->name("profile.setUser");



    /***Financeiro Gerente */
    Route::get('/gerente',"App\Http\Controllers\Admin\GerenteController@index")->name('gerente.index');
    Route::post('/gerente/pegartodos',"App\Http\Controllers\Admin\GerenteController@pegarTodososDados")->name('gerente.todos.valores.usuario');

    Route::get('/gerente/listagem',"App\Http\Controllers\Admin\GerenteController@listagem")->name('gerente.listagem.em_geral');
   
    Route::get('/gerente/comissao/{id}',"App\Http\Controllers\Admin\GerenteController@listarComissao")->name('gerente.comissao.listar');
    Route::get('/gerente/detalhe/{id_contrato}',"App\Http\Controllers\Admin\GerenteController@detalhe")->name('gerente.listagem.detalhe');
    Route::get('/gerente/listar/comissao',"App\Http\Controllers\Admin\GerenteController@listarUserComissoesAll")->name('gerente.listagem.comissao');
    Route::get('/gerente/listagem/comissao_mes_atual/{id}',"App\Http\Controllers\Admin\GerenteController@comissaoMesAtual")->name('gerente.listagem.comissao_mes_atual');
    Route::get('/gerente/listagem/recebidas/coletivo/{id}',"App\Http\Controllers\Admin\GerenteController@recebidasColetivo")->name('gerente.listagem.recebidas.coletivo');
    Route::get('/gerente/listagem/zerar/tabelas',"App\Http\Controllers\Admin\GerenteController@zerarTabelas")->name('gerente.listagem.zerar.tabelas');
    


    Route::get('/gerente/comissao/confirmadas/{id}',"App\Http\Controllers\Admin\GerenteController@comissaoListagemConfirmadas")->name('gerente.listagem.confirmadas');
    Route::get('/gerente/comissao/coletivo/confirmadas/{id}',"App\Http\Controllers\Admin\GerenteController@comissaoListagemConfirmadasColetivo")->name('gerente.listagem.coletivo.confirmadas');
    //Route::get('/gerente/comissao/empresarial/confirmadas/{id}',"App\Http\Controllers\Admin\GerenteController@comissaoListagemConfirmadasEmpresarial")->name('gerente.listagem.empresarial.confirmadas');
    Route::get('/gerente/listagem/empresarial/recebidas/{id}',"App\Http\Controllers\Admin\GerenteController@recebidoEmpresarial")->name('gerente.listagem.empresarial.recebidas');
    
    
    
    
    Route::get('/gerente/listagem/comissao_mes_diferente/{id}',"App\Http\Controllers\Admin\GerenteController@comissaoMesDiferente")->name('gerente.listagem.comissao_mes_diferente');
   
   
    Route::get('/gerente/coletivo/listar/{id}',"App\Http\Controllers\Admin\GerenteController@coletivoAReceber")->name('gerente.listagem.coletivo.areceber');
    Route::get('/gerente/empresarial/listar/{id}',"App\Http\Controllers\Admin\GerenteController@empresarialAReceber")->name('gerente.listagem.empresarial.areceber');
    
    
    
    Route::post('/gerente/mudar_status',"App\Http\Controllers\Admin\GerenteController@mudarStatus")->name('gerente.mudar_status');
    Route::get('/gerente/criar_pdf_pagamento',"App\Http\Controllers\Admin\GerenteController@criarPdfPagamento")->name('comissao.create.pdf');
    Route::post('/gerente/mudarcomisao/corretora',"App\Http\Controllers\Admin\GerenteController@mudarComissaoCorretora")->name('gerente.mudar.valor.corretora');
    
    Route::post('/gerente/mudarcomisao/corretor/gerente',"App\Http\Controllers\Admin\GerenteController@mudarComissaoCorretor")->name('gerente.mudar.valor.corretor');
    Route::post('/gerente/mudarcomisao/corretor/pago',"App\Http\Controllers\Admin\GerenteController@mudarComissaoCorretorGerente")->name('gerente.mudar.valor.pago');





    Route::post('/gerente/administradorapagou/comissao',"App\Http\Controllers\Admin\GerenteController@administradoraPagouComissao")->name('gerente.administradorapagoucomissao');
    Route::post('/gerente/finalizar/pagamento',"App\Http\Controllers\Admin\GerenteController@finalizarPagamento")->name('gerente.finalizar.pagamento');
    Route::get('/gerente/listarcontratosemgeral',"App\Http\Controllers\Admin\GerenteController@listarcontratos")->name('gerente.listarcontratos.geral');
    Route::get('/gerente/contrato/{id}',"App\Http\Controllers\Admin\GerenteController@listarcontratosDetalhe")->name('gerente.contrato.detalhe');

    Route::get('/gerente/ver/{id_plano?}/{id_tipo?}/{ano?}/{mes?}/{id_user?}',"App\Http\Controllers\Admin\GerenteController@verDetalheCard")->name('gerente.contrato.ver.detalhe.card');
    Route::get('/gerente/show/{id_plano?}/{id_tipo?}/{ano?}/{mes?}/{id_user?}',"App\Http\Controllers\Admin\GerenteController@showDetalheCard")->name('gerente.contrato.show.detalhe.card');
    
    
    Route::get('/gerente/all/ver/{id_estagio}',"App\Http\Controllers\Admin\GerenteController@showDetalhesDadosTodosAll")->name('gerente.contrato.show.detalhes.todos.visualizar');
    Route::get('/gerente/all/todos/show/{estagio}',"App\Http\Controllers\Admin\GerenteController@showTodosDetalheCard")->name('gerente.contrato.show.detalhes.todos');


    Route::post('/gerente/antecipar/parcela',"App\Http\Controllers\Admin\GerenteController@aptarPagamento")->name('gerente.aptar.pagamento');



    /***Fim Financeiro Gerente */

    /****************************************************************Configurações******************************************************************/

        /* Corretora **/
        Route::get('/corretora',"App\Http\Controllers\Admin\CorretoraController@index")->name('corretora.index');
        Route::post('/corretora',"App\Http\Controllers\Admin\CorretoraController@store")->name('corretora.store');
        /* Fim  Corretora **/

        /**Administradoras*/
        Route::get("/administradora","App\Http\Controllers\Admin\AdministradoraController@index")->name('administradoras.index');
        Route::get("/administradora/list","App\Http\Controllers\Admin\AdministradoraController@list")->name('administradoras.list');
        Route::post("/administradora/cadastrar","App\Http\Controllers\Admin\AdministradoraController@cadastrarAjax")->name('administradoras.store');
        /**Fim Administradoras*/


        /**Tabela Origem */
        Route::get("/tabela_origem","App\Http\Controllers\Admin\TabelaOrigemControlller@index")->name('tabela_origem.index');
        Route::get("/tabela_origem/list","App\Http\Controllers\Admin\TabelaOrigemControlller@list")->name('tabela_origem.list');
        Route::post("/tabela_origem/store","App\Http\Controllers\Admin\TabelaOrigemControlller@store")->name('tabela_origem.store');
        /**Fim Tabela Origem */

        /**Planos*/
        Route::get("/planos","App\Http\Controllers\Admin\PlanoController@index")->name('planos.index');
        Route::get("/planos/list","App\Http\Controllers\Admin\PlanoController@list")->name('planos.list');
        Route::post("/planos/store","App\Http\Controllers\Admin\PlanoController@store")->name('planos.store');
        /**Fim Planos*/


        /**Tabela de Preços */
        Route::get("/tabela","App\Http\Controllers\Admin\TabelaController@index")->name('tabela.index');  
        
        Route::post("/tabela/search","App\Http\Controllers\Admin\TabelaController@pesquisar")->name("tabela.pesquisar");
        Route::get("/tabela/search","App\Http\Controllers\Admin\TabelaController@search")->name("tabela.search");

        Route::post("/tabela","App\Http\Controllers\Admin\TabelaController@store")->name("store.tabela");
        
        Route::post("/tabelas/pegar/cidades/administradoras","App\Http\Controllers\Admin\TabelaController@pegarCidadeAdministradora")->name("cidades.administradoras.pegar");
        Route::post("/tabela/orcamento/alterar","App\Http\Controllers\Admin\TabelaController@edit")->name("tabela.edit.valor");
        
        
        /** Fim Tabela de Preços */
           
        
        /****Corretor*****/

        Route::get("/corretores","App\Http\Controllers\Admin\CorretorController@index")->name('corretor.index');
        Route::post("/corretores","App\Http\Controllers\Admin\CorretorController@store")->name('corretores.store');


        /****Fim Corretor*****/



    /*************************************************************Fim Configurações****************************************************************/



    


    
});

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
