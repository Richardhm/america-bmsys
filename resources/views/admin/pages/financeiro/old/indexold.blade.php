@extends('adminlte::page')
@section('title', 'Financeiro')
@section('plugins.Sweetalert2',true)
@section('plugins.Datatables', true)

@section('content_top_nav_right')
    <li>
        <a class="text-white nav-link" href="{{route('orcamento.search.home')}}">Tabela de Preço</a>    
    </li>
    <li>
        <a class="text-white nav-link" href="{{route('home.administrador.consultar')}}">Consultar</a>   
    </li>
    <li>
        <a class="toda_janela nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
    </li>   
@stop


@section('content')
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>




    <input type="hidden" id="janela_atual" value="aba_individual">
    <div id="container_mostrar_comissao" class="ocultar"></div>

    <input type="hidden" id="janela_ativa" name="janela_ativa" value="aba_individual">

    <div class="container_div_info">
        
    </div>

    <div class="modal fade" id="carteirinhaModal" tabindex="-1" aria-labelledby="carteirinhaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carteirinhaModalLabel">Carteirinha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" name="colocar_carteirinha" id="colocar_carteirinha">
                        @csrf
                        <div class="d-flex">
                            <div style="flex-basis:100%;margin-right:2%;margin-bottom:10px;">
                                <label for="arquivo">Carteirinha:</label>
                                <input type="text" name="cateirinha" id="cateirinha" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div id="carteirinha_error"></div>


                        <input type="hidden" name="id_cliente" id="carteirinha_id_input" />
                        <input type="submit" value="Enviar" class="btn btn-block btn-info">
                   </form>
                </div>            
            </div>
        </div>
    </div>


    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" name="formulario_upload" id="formulario_upload" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex">
                            <div style="flex-basis:90%;margin-right:2%;">
                                <label for="arquivo">Arquivo:</label>
                                <input type="file" name="arquivo_upload" id="arquivo_upload" class="form-control form-control-sm">
                            </div>
                            <div class="btn btn-danger d-flex align-self-end div_icone_arquivo_upload" style="flex-basis:5%;">
                                <i class="fas fa-window-close fa-lg"></i>
                            </div>
                        </div>

                        <div class="d-flex my-2">
                            <div style="flex-basis:90%;margin-right:2%;">
                                <button class="btn btn-warning btn-sm btn-block atualizar_dados text-white">Atualizar Dados</button>
                            </div>
                            <div class="btn btn-danger d-flex align-self-end div_icone_atualizar_dados">
                                <i class="fas fa-window-close fa-lg"></i>
                            </div>
                            
                        </div>

                        <div class="d-flex my-2">
                            <div style="flex-basis:90%;margin-right:2%;">
                                <button class="btn btn-info btn-sm btn-block sincronizar_baixas">Sincronizar Baixas</button>
                            </div>
                            <div class="btn btn-danger d-flex align-self-end">
                                <i class="fas fa-window-close fa-lg"></i>
                            </div>
                        </div>
                   </form>
                </div>            
            </div>
        </div>
    </div>


    <div class="modal fade" id="uploadModalColetivo" tabindex="-1" aria-labelledby="uploadModalLabelColetivo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabelColetivo">Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" name="formulario_upload_coletivo" id="formulario_upload_coletivo" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex">
                            <div style="flex-basis:90%;margin-right:2%;">
                                <label for="arquivo">Arquivo:</label>
                                <input type="file" name="arquivo_upload_coletivo" id="arquivo_upload_coletivo" class="form-control form-control-sm">
                            </div>
                            <div class="btn btn-danger d-flex align-self-end div_icone_arquivo_upload" style="flex-basis:5%;">
                                <i class="fas fa-window-close fa-lg"></i>
                            </div>
                        </div>

                        

                        
                   </form>
                </div>            
            </div>
        </div>
    </div>





    <!-- Modal -->
    

    

    <div class="modal fade" id="dataBaixaIndividualModal" tabindex="-1" role="dialog" aria-labelledby="dataBaixaIndividualLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataBaixaIndividualLabel">Data Da Baixa?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="data_da_baixa_individual" id="data_da_baixa_individual" method="POST">
                    <input type="date" name="date_baixa_individual" id="date_baixa_individual" class="form-control form-control-sm">
                    <input type="hidden" name="comissao_id_baixa_individual" id="comissao_id_baixa_individual">                   
                    <div id="error_data_baixa_individual">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>
            </div>
        </div>
    </div>

    

    

    
    <div>
        <ul class="list_abas">
            <li data-id="aba_individual" class="ativo">Individual</li>
            <li data-id="aba_coletivo">Coletivo</li>
            <li data-id="aba_empresarial">Empresarial</li>
            <!-- <li data-id="aba_sem_carteirinha">Sem Carteirinha</li>   -->
        </ul>
    </div>



    <section class="conteudo_abas">
        <!--------------------------------------INDIVIDUAL------------------------------------------>
        <main id="aba_individual">
           
            <section class="d-flex justify-content-between" style="flex-wrap: wrap;align-content: flex-start;">
            
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">                    

                    <div class="d-flex justify-content-between mb-1">
                        <span class="btn btn-upload" style="background-color:#123449;color:#FFF;font-size:1.2em;flex-basis:49%;">Upload</span>   
                        <span class="btn btn-atualizar" style="background-color:#123449;color:#FFF;font-size:1.2em;flex-basis:49%;">Atualizar</span>
                    </div>

                    <div class="mb-1 py-2" style="background-color:#123449;border-radius:5px;margin-bottom:3px;">   

                        <div class="d-flex justify-content-around" style="flex-wrap:wrap;">

                            <div style="display:flex;flex-basis:48%;">
                                <select id="mudar_ano_table" class="form-control">
                                    <option value="2023" selected>2023</option>
                                </select>
                            </div>

                            <div style="display:flex;flex-basis:48%;">
                                <select id="mudar_mes_table" class="form-control">
                                    <option value="" class="text-center">-Meses-</option>
                                    <option value="01">Janeiro</option>
                                    <option value="02">Fevereiro</option>
                                    <option value="03">Março</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Maio</option>
                                    <option value="06" selected>Junho</option>
                                    <option value="07">Julho</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>

                            <select style="flex-basis:98%;" id="select_usuario_individual" class="form-control my-2 mx-auto">
                                <option value="todos" class="text-center" data-id="0">---Escolher Corretor---</option>
                                @foreach($users as $u) 
                                    <option value="{{$u->name}}" data-id="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                            </select>


                        </div>

                        <ul style="list-style:none;margin:0;padding:4px 0;" id="list_individual_begin">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" class="individual">
                                <span>Contratos:</span>
                                <span class="badge badge-light total_por_orcamento" style="width:80px;text-align:right;">0</span>  
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" class="individual">
                                <span>Vidas:</span>
                                <span class="badge badge-light total_por_vida" style="width:80px;text-align:right;">0</span>  
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;" class="individual">
                                <span>Valor:</span>
                                <span class="badge badge-light total_por_page" style="width:80px;text-align:right;">0</span>  
                            </li>
                        </ul>
                    </div>
                    <div style="background-color:red;border-radius:5px;margin-bottom:3px;">   
                        <ul style="list-style:none;margin:0;padding:6px 0;" id="atrasado_corretor">
                            <li style="padding:0px 1px;display:flex;justify-content:space-between;" id="" class="individual">
                                <span>Atrasados</span>
                                <span class="badge badge-light individual_quantidade_atrasado" style="width:45px;text-align:right;">{{$qtd_individual_atrasado}}</span>  
                            </li>
                        </ul>
                    </div>

                    <div style="background-color:#123449;border-radius:5px;margin-bottom:3px;">   
                        <ul style="list-style:none;margin:0;padding:6px 0;" id="finalizado_corretor">
                            <li style="padding:0px 1px;display:flex;justify-content:space-between;" id="aguardando_pagamento_6_parcela_individual" class="individual">
                                <span>Finalizado</span>
                                <span class="badge badge-light individual_quantidade_6_parcela" style="width:45px;text-align:right;">{{$qtd_individual_parcela_06}}</span>  
                            </li>
                        </ul>
                    </div>

                    <div style="background-color:#123449;border-radius:5px;margin-bottom:3px;">   
                        <ul style="list-style:none;margin:0;padding:6px 0;" id="cancelado_corretor">
                            <li style="padding:0px 1px;display:flex;justify-content:space-between;margin-bottom:4px;" id="cancelado_individual" class="individual">
                                <span>Cancelados</span>
                                <span class="badge badge-light individual_quantidade_cancelado" style="width:45px;text-align:right;">{{$qtd_individual_cancelado}}</span>
                            </li>
                        </ul>
                    </div>




                    <div style="margin:0 0 5px 0;padding:0;background-color:#123449;border-radius:5px;">
                        
               

                        <ul style="margin:0;padding:0;list-style:none;" id="listar_individual">
                       
                            <li style="padding:0px 4px;display:flex;justify-content:space-between;margin-bottom:4px;" id="aguardando_pagamento_2_parcela_individual" class="individual">
                               <span>Pag. 2º Parcela</span>
                               <span class="badge badge-light individual_quantidade_2_parcela" style="width:45px;text-align:right;">{{$qtd_individual_parcela_02}}</span>                        
                            </li>

                            <li style="padding:0px 4px;display:flex;justify-content:space-between;margin-bottom:4px;" id="aguardando_pagamento_3_parcela_individual" class="individual">
                               <span>Pag. 3º Parcela</span>
                               <span class="badge badge-light individual_quantidade_3_parcela" style="width:45px;text-align:right;">{{$qtd_individual_parcela_03}}</span>                        
                            </li>

                            <li style="padding:0px 4px;display:flex;justify-content:space-between;margin-bottom:4px;" id="aguardando_pagamento_4_parcela_individual" class="individual">
                               <span>Pag. 4º Parcela</span>
                               <span class="badge badge-light individual_quantidade_4_parcela" style="width:45px;text-align:right;">{{$qtd_individual_parcela_04}}</span>                        
                            </li>

                            <li style="padding:0px 4px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_5_parcela_individual" class="individual">
                               <span>Pag. 5º Parcela</span>
                               <span class="badge badge-light individual_quantidade_5_parcela" style="width:45px;text-align:right;">{{$qtd_individual_parcela_05}}</span>                        
                            </li>

                                                   
                        </ul>
                   </div>


                    


                </div>
                <!--Fim Coluna da Esquerda  -->


                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:83%;">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                        <table id="tabela_individual" class="table table-sm listarindividual">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Orçamento</th>
                                    <th>Corretor</th>
                                    <th>Cliente</th>
                                    <th>CPF</th>
                                    <th>Vidas</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>                                  
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            
                        </table>   
                    </div>
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                <!---------DIREITA-------------->    
                  
                <!---------FIM DIREITA-------------->    
            </section>
       </main><!-------------------------------------DIV FIM Individial------------------------------------->     
       <!-------------------------------------FIM Individial------------------------------------->

       <!------------------------------------------COLETIVO---------------------------------------------------->
       <main id="aba_coletivo" class="ocultar">
             <section class="d-flex justify-content-between" style="flex-wrap: wrap;">
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">
                    

                    

                    <div class="d-flex justify-content-between mb-1">
                        <span class="btn" style="background-color:#123449;color:#FFF;font-size:1.2em;flex-basis:49%;">
                            <a class="text-center text-white" href="{{route('contratos.create.coletivo')}}">Cadastrar</a>
                        </span>   
                        <span class="btn btn_upload_coletivo" style="background-color:#123449;color:#FFF;font-size:1.2em;flex-basis:49%;">
                            Upload
                        </span>
                    </div>


                    


                    <div class="py-2" style="background-color:#123449;border-radius:5px;">   

                        <div class="d-flex justify-content-around" style="flex-wrap:wrap;">

                            <div style="display:flex;flex-basis:48%;">
                                <select id="mudar_ano_table_coletivo" class="form-control">
                                    <option value="todos" class="text-center">-Anos-</option>
                                    @foreach($anos_coletivo as $ac)
                                        <option value="{{$ac->anos}}" {{date('Y') == $ac->anos ? 'selected' : ''}}>{{$ac->anos}}</option>
                                        
                                    @endforeach
                                    
                                </select>
                            </div>

                            <div style="display:flex;flex-basis:48%;margin-bottom:1%;">
                                <select id="mudar_mes_table_coletivo" class="form-control">
                                    <option value="todos" class="text-center">-Meses-</option>
                                    @foreach($meses as $k =>$m)
                                        <option value="{{$k}}" {{date('m') == $k ? 'selected' : ''}}>{{$m}}</option>             
                                    @endforeach
                                </select>
                            </div>

                            <select class="form-control" style="flex-basis:98%;" id="select_usuario">
                                <option value="todos" class="text-center">---Escolher Corretor---</option>
                                @foreach($users as $u) 
                                    <option value="{{$u->name}}" data-id="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                            </select>

                            <select class="my-1 form-control" style="flex-basis:98%;" id="select_coletivo_administradoras">
                                <option value="todos" class="text-center">---Administradora---</option>   
                                @foreach($administradoras as $ad)
                                    <option value="{{$ad->nome}}" data-id="{{$ad->id}}">{{$ad->nome}}</option>
                                @endforeach
                                

                            </select>


                        </div>

                        <ul style="list-style:none;margin:0;padding:4px 0;" id="list_coletivo_begin">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" class="coletivo">
                                <span>Contratos:</span>
                                <span class="badge badge-light total_por_orcamento_coletivo" style="width:80px;text-align:right;">{{$contratos_coletivo_total}}</span>  
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" class="coletivo">
                                <span>Vidas:</span>
                                <span class="badge badge-light total_por_vida_coletivo" style="width:80px;text-align:right;">{{$qtd_total_vidas_coletivo ?? 0}}</span>  
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;" class="coletivo">
                                <span>Valor:</span>
                                <span class="badge badge-light total_por_page_coletivo" style="width:80px;text-align:right;">{{number_format($qtd_total_valor_coletivo,2,",",".")}}</span>  
                            </li>
                        </ul>
                    </div>

                    <div style="background-color:red;border-radius:5px;margin:1px 0;">   
                        <ul style="list-style:none;margin:0;padding:5px 0;" id="atrasado_corretor_coletivo">
                            <li style="padding:0px 3px;display:flex;justify-content:space-between;" id="" class="coletivo">
                                <span>Atrasados</span>
                                <span class="badge badge-light coletivo_quantidade_atrasado" style="width:45px;text-align:right;">0</span>  
                            </li>
                        </ul>
                    </div>

                    <div style="border-radius:5px;margin:1px 0;background-color:#123449;">   
                        <ul style="list-style:none;margin:0;padding:5px 0;" id="finalizado_corretor_coletivo">
                            <li style="padding:0px 3px;display:flex;justify-content:space-between;" id="" class="coletivo">
                                <span>Finalizado</span>
                                <span class="badge badge-light quantidade_coletivo_finalizado" style="width:45px;text-align:right;">{{$qtd_coletivo_finalizados}}</span>  
                            </li>
                        </ul>
                    </div>


                    <!-- <div style="background-color:#123449;border-radius:5px;margin:3px 0px;">   
                        <ul style="list-style:none;margin:0;padding:10px 0;">
                            <li style="padding:0px 4px;display:flex;justify-content:space-between;margin-bottom:5px;" class="coletivo">
                                <span>Orçamento:</span>
                                <span class="badge badge-light total_por_orcamento_coletivo" style="width:80px;text-align:right;">0</span>  
                            </li>
                            <li style="padding:0px 4px;display:flex;justify-content:space-between;margin-bottom:5px;" class="coletivo">
                                <span>Vidas:</span>
                                <span class="badge badge-light total_por_vida_coletivo" style="width:80px;text-align:right;">0</span>  
                            </li>
                            <li style="padding:0px 4px;display:flex;justify-content:space-between;" class="coletivo">
                                <span>Valor:</span>
                                <span class="badge badge-light total_por_page_coletivo" style="width:80px;text-align:right;">0</span>  
                            </li>
                        </ul>
                    </div> -->



                    
                    <div style="margin:0 0 3px 0;padding:0;background-color:#123449;border-radius:5px;">
                                   
                        <ul style="margin:0;padding:0;list-style:none;" id="listar">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="em_analise_coletivo" class="fundo coletivo ">
                                <span>Em Analise</span>
                                <span class="badge badge-light coletivo_quantidade_em_analise" style="width:45px;text-align:right;vertical-align: middle;">{{$qtd_coletivo_em_analise}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="emissao_boleto_coletivo" class="coletivo">
                                <span>Emissão Boleto</span>
                                <span class="badge badge-light coletivo_quantidade_emissao_boleto" style="width:45px;text-align:right;">{{$qtd_coletivo_emissao_boleto}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_adesao_coletivo" class="coletivo">
                                <span>Pag. Adesão</span>
                                <span class="badge badge-light coletivo_quantidade_pagamento_adesao" style="width:45px;text-align:right;">{{$qtd_coletivo_pg_adesao}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_vigencia_coletivo" class="coletivo">
                                <span>Pag. Vigência</span>
                                <span class="badge badge-light coletivo_quantidade_pagamento_vigencia" style="width:45px;text-align:right;">{{$qtd_coletivo_pg_vigencia}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_segunda_parcela" class="coletivo">
                                <span>Pag. 2º Parcela</span>
                                <span class="badge badge-light coletivo_quantidade_segunda_parcela" style="width:45px;text-align:right;">{{$qtd_coletivo_03_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_terceira_parcela" class="coletivo">
                                <span>Pag. 3º Parcela</span>
                                <span class="badge badge-light coletivo_quantidade_terceira_parcela" style="width:45px;text-align:right;">{{$qtd_coletivo_04_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_quarta_parcela" class="coletivo">
                                <span>Pag. 4º Parcela</span>
                                <span class="badge badge-light coletivo_quantidade_quarta_parcela" style="width:45px;text-align:right;">{{$qtd_coletivo_05_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_quinta_parcela" class="coletivo">
                                <span>Pag. 5º Parcela</span>
                                <span class="badge badge-light coletivo_quantidade_quinta_parcela" style="width:45px;text-align:right;">{{$qtd_coletivo_06_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="pagamento_sexta_parcela" class="coletivo">
                                <span>Pag. 6º Parcela</span>
                                <span class="badge badge-light coletivo_quantidade_sexta_parcela" style="width:45px;text-align:right;">{{$qtd_coletivo_07_parcela}}</span>
                            </li>
                        </ul>
                    </div>

                    <div style="background-color:#123449;border-radius:5px;">   
                   
                        <ul style="list-style:none;margin:0;padding:5px 0;" id="grupo_finalizados">
                           
                            <li style="padding:0px 3px;display:flex;justify-content:space-between;margin-bottom:4px;" id="cancelado_coletivo" class="coletivo">
                                <span>Cancelados</span>
                                <span class="badge badge-light quantidade_coletivo_cancelados" style="width:45px;text-align:right;">{{$qtd_coletivo_cancelados}}</span>
                            </li>
                        </ul>
                    </div>


                </div>    
                <!--FIM COLUNA DA ESQUERDA-->


                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:83%;">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                        <table id="tabela_coletivo" class="table table-sm listardados">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Orçamento</th>
                                    <th>Corretor</th>
                                    <th>Cliente</th>
                                    <th>Administradora</th>
                                    <th>CPF</th>
                                    <th>Vidas</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div> 
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                <!--COLUNA DA DIREITA-->    
                

            </section>

       </main>

       <main id="aba_empresarial" class="ocultar">
           
            <section class="d-flex justify-content-between" style="flex-wrap: wrap;">
                
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">                    
                    
                    <div style="background-color:#123449;border-radius:5px;margin:1px 0;">   
                        <ul style="list-style:none;margin:0;padding:5px 0;" id="atrasado_corretor_coletivo">
                            <li style="padding:0px 3px;display:flex;text-align:center;justify-content:center;">
                                <a class="text-center" href="{{route('contratos.create.empresarial')}}" style="color:#FFF;font-size:1.2em;text-align:center;display:flex;flex-basis:100%;justify-content: center;;">Financeiro</a>
                            </li>
                        </ul>
                    </div>


                    <div class="mb-1 mt-1 py-2" style="background-color:#123449;border-radius:5px;margin-bottom:3px;">   

                        <div class="d-flex justify-content-around" style="flex-wrap:wrap;">

                            <div style="display:flex;flex-basis:48%;">
                                <select id="mudar_ano_table_empresarial" class="form-control">
                                    <option value="" class="text-center">-Anos-</option>
                                    <option value="2022">2022</option>
                                    <option value="2023" selected>2023</option>
                                </select>
                            </div>

                            <div style="display:flex;flex-basis:48%;">
                                <select id="mudar_mes_table_empresarial" class="form-control">
                                    <option value="" class="text-center">-Meses-</option>
                                    <option value="01">Janeiro</option>
                                    <option value="02">Fevereiro</option>
                                    <option value="03">Março</option>
                                    <option value="04" selected>Abril</option>
                                    <option value="05">Maio</option>
                                    <option value="06">Junho</option>
                                    <option value="07">Julho</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>

                            <select style="flex-basis:98%;" name="mudar_user_empresarial" id="mudar_user_empresarial" class="form-control my-2 mx-auto">
                                <option value="todos" class="text-center" data-id="0">---Escolher Corretor---</option>
                                @foreach($users as $u) 
                                    <option value="{{$u->name}}" data-id="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                            </select>

                        </div>

                        <ul style="list-style:none;margin:0;padding:4px 0;" id="list_empresarial_begin">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" class="empresarial">
                                <span>Contratos:</span>
                                <span class="badge badge-light total_por_orcamento_empresarial" style="width:80px;text-align:right;">0</span>  
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" class="empresarial">
                                <span>Vidas:</span>
                                <span class="badge badge-light total_por_vida_empresarial" style="width:80px;text-align:right;">0</span>  
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;" class="empresarial">
                                <span>Valor:</span>
                                <span class="badge badge-light total_por_page_empresarial" style="width:80px;text-align:right;">0</span>  
                            </li>
                        </ul>
                    </div>


                    <div style="background-color:red;border-radius:5px;margin:1px 0;">   
                        <ul style="list-style:none;margin:0;padding:5px 0;" id="atrasado_corretor_empresarial">
                            <li style="padding:0px 3px;display:flex;justify-content:space-between;" id="" class="empresarial">
                                <span>Atrasados</span>
                                <span class="badge badge-light coletivo_quantidade_atrasado" style="width:45px;text-align:right;">0</span>  
                            </li>
                        </ul>
                    </div>

                    <div style="border-radius:5px;margin:1px 0;background-color:#123449;">   
                        <ul style="list-style:none;margin:0;padding:5px 0;" id="finalizado_corretor_empresarial">
                            <li style="padding:0px 3px;display:flex;justify-content:space-between;" id="" class="empresarial">
                                <span>Finalizado</span>
                                <span class="badge badge-light quantidade_coletivo_finalizado" style="width:45px;text-align:right;">0</span>  
                            </li>
                        </ul>
                    </div>


                    <div style="margin:0 0 5px 0;padding:5px 0 0 0;background-color:#123449;border-radius:5px;">
                        
                        <ul style="margin:0;padding:0;list-style:none;" id="listar_empresarial">
                            
                           <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_em_analise_empresarial"  class="empresarial">
                                <span>Em Análise</span>
                               <span class="badge badge-light empresarial_quantidade_em_analise" style="width:45px;text-align:right;">{{$qtd_empresarial_em_analise}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_1_parcela_empresarial" class="empresarial">
                                <span>Pag. 1º Parcela</span>
                               <span class="badge badge-light empresarial_quantidade_1_parcela" style="width:45px;text-align:right;">{{$qtd_empresarial_parcela_01}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_2_parcela_empresarial" class="empresarial">
                               <span>Pag. 2º Parcela</span>
                               <span class="badge badge-light empresarial_quantidade_2_parcela" style="width:45px;text-align:right;">{{$qtd_empresarial_parcela_02}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_3_parcela_empresarial" class="empresarial">
                               <span>Pag. 3º Parcela</span>
                               <span class="badge badge-light empresarial_quantidade_3_parcela" style="width:45px;text-align:right;">{{$qtd_empresarial_parcela_03}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_4_parcela_empresarial" class="empresarial">
                               <span>Pag. 4º Parcela</span>
                               <span class="badge badge-light empresarial_quantidade_4_parcela" style="width:45px;text-align:right;">{{$qtd_empresarial_parcela_04}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_5_parcela_empresarial" class="empresarial">
                               <span>Pag. 5º Parcela</span>
                               <span class="badge badge-light empresarial_quantidade_5_parcela" style="width:45px;text-align:right;">{{$qtd_empresarial_parcela_05}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_6_parcela_empresarial" class="empresarial">
                               <span>Pag. 6º Parcela</span>
                               <span class="badge badge-light empresarial_quantidade_6_parcela" style="width:45px;text-align:right;">{{$qtd_empresarial_parcela_06}}</span>                        
                            </li>
                        </ul>
                    </div>


                    <div style="background-color:#123449;border-radius:5px;">   
                        <ul style="list-style:none;margin:0;padding:10px 0;" id="grupo_finalizados_empresarial">
                            
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_cancelado_empresarial" class="empresarial">
                               <span>Cancelado</span>
                               <span class="badge badge-light empresarial_quantidade_cancelado" style="width:45px;text-align:right;">{{$qtd_empresarial_cancelado}}</span>                        
                            </li>
                        </ul>
                    </div>







                </div>
                <!--Fim Coluna da Esquerda  -->

                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:83%;">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                        <table id="tabela_empresarial" class="table table-sm listarempresarial">
                        
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Orçamento</th>
                                    <th>Corretor</th>
                                    <th>Cliente</th>
                                    <th>CNPJ</th>
                                    <th>Vidas</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div> 
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                <!---Coluna Direita--->
                
                <!---------FIM DIREITA----------->
        </main>


        <main id="aba_sem_carteirinha" class="ocultar">    
            <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                <table id="tablesemcarteirinha" class="table table-sm table_sem_carteirinha">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Orçamento</th>  
                            <th>Corretor</th>  
                            <th>Cliente</th>  
                            <th>CPF</th>  
                            <th>Vidas</th>  
                            <th>Editar</th>  
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>    
        </main>    

    </section>

    
   
@stop  

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>   
    <script src="{{asset('js/jquery.form.js')}}"></script>   
    <script src="{{asset('js/jquery.ajax-progress.js')}}"></script>   
    
    <script>
        $(document).ready(function(){

            

            let url = window.location.href.indexOf("?");
            if(url != -1) {
                
                var b =  window.location.href.substring(url);
                var alvo = b.split("=")[1];
                if(alvo == "coletivo") {
                    
                    $('.list_abas li').removeClass('ativo');
                    $('.list_abas li:nth-child(2)').addClass("ativo");
                    $('.conteudo_abas main').addClass('ocultar');
                    $('#aba_coletivo').removeClass('ocultar');
                    var c = window.location.href.replace(b,"");
                    window.history.pushState({path:c},'',c);
                    
                    
                    $("#janela_atual").val("aba_coletivo");

                                  
                    //$("listar_abas").trigger('click');
                    //$(".list_abas li").trigger('click');
                    
                    //

                    //$('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                    //table.ajax.url("{{ route('financeiro.coletivo.em_geral') }}").load();
                    //$("#list_coletivo_begin").trigger('click');
                    //$('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                    
                    // table.ajax.url("{{ route('financeiro.coletivo.em_geral') }}").load();
                    //table.ajax.url("{{ route('financeiro.coletivo.em_analise') }}").load();
                } 
                if(alvo == "empresarial") {
                    $('.list_abas li').removeClass('ativo');
                    $('.list_abas li:nth-child(3)').addClass("ativo");
                    $('.conteudo_abas main').addClass('ocultar');
                    $("#aguardando_em_analise_empresarial").addClass("text")
                    $("#aguardando_em_analise_empresarial").addClass('textoforte-list');
                    $('#aba_empresarial').removeClass('ocultar');
                    
                    var c = window.location.href.replace(b,"");
                    window.history.pushState({path:c},'',c); 
                    
                }
            }

            $("#listar_geral").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes') }}").load();                
                $(".container_edit").addClass('ocultar')
                //adicionarReadonly();
                $("#atrasado_corretor").removeClass('textoforte-list');
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("#all_pendentes_individual").removeClass('textoforte-list');
                $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
                limparFormulario();
            });
    
            $("#mudar_ano_table").on('change',function(){
                var ano = $(this).val();
                var mes = $("#mudar_mes_table").val() != null ? $("#mudar_mes_table").val() : null;
                table_individual.ajax.url(`/admin/financeiro/individual/mudar_ano/${ano}/${mes}`).load();
            });

            $("#mudar_mes_table").on('change',function(){
                var mes = $(this).val() != "" ? $(this).val() : "00";
                console.log(totalMes());

                var ano = $("#mudar_ano_table").val() != null ? $("#mudar_ano_table").val() : null;
                table_individual.ajax.url(`/admin/financeiro/individual/mudar_mes/${mes}/${ano}`).load();
            });

            function totalMes() {
                return $("#select_usuario_individual").val();
            }

            $("#mudar_ano_table_coletivo").on('change',function(){
                var ano = $(this).val();
                var mes = $("#mudar_mes_table_coletivo").val() != null ? $("#mudar_mes_table_coletivo").val() : null;
                table.ajax.url(`/admin/financeiro/coletivo/mudar_ano/${ano}/${mes}`).load();  
            });

            $("#mudar_mes_table_coletivo").on('change',function(){
                var mes = $(this).val();
                var ano = $("#mudar_ano_table_coletivo").val() != null ? $("#mudar_ano_table_coletivo").val() : null;
                table.ajax.url(`/admin/financeiro/coletivo/mudar_mes/${mes}/${ano}`).load();
            });

            // $("#mudar_ano_table_coletivo").on('change',function(){
            //     var ano = $(this).val();
            //     var mes = $("#mudar_mes_table_coletivo").val() != null ? $("#mudar_mes_table_coletivo").val() : null;
            //     table.ajax.url(`/admin/financeiro/coletivo/mudar_ano/${ano}/${mes}`).load();  
            // });

            // $("#mudar_mes_table_coletivo").on('change',function(){
            //     var mes = $(this).val();
            //     var ano = $("#mudar_ano_table_coletivo").val() != null ? $("#mudar_ano_table_coletivo").val() : null;
            //     table.ajax.url(`/admin/financeiro/coletivo/mudar_mes/${mes}/${ano}`).load();
            // });



            mudar_user_empresarial = "";

            $("#mudar_user_empresarial").on('change',function(){
                mudar_user_empresarial = $(this).val();
                if($(this).val() != "todos") {
                    tableempresarial.column(2).search($(this).val()).draw();
                } else {
                    var val = "";
                    tableempresarial.column(2).search(val).draw();
                    tableempresarial.column(2).search(val ? '^' + val + '$' : '', true, false).draw();
                }
            });

            // $("#uploadModal").on('shown.bs.modal', function (event) {
            //     $("#uploadModal").css("z-index","1");
            //     //$("#error_data_baixa_individual").html('');
            // });

            $(".btn-upload").on('click',function(){
                $('#uploadModal').modal('show')
            });

            $(".btn_upload_coletivo").on('click',function(){
                
                $('#uploadModalColetivo').modal('show')
            });

            $(".btn-atualizar").on('click',function(){
                var load = $(".ajax_load");
                $.ajax({
                    url:"{{route('financeiro.sincronizar.baixas.jaexiste')}}",
                    method:"POST",
                    beforeSend: function () {
                        load.fadeIn(200).css("display", "flex");
                    },
                    success:function(res) {
                        window.location.reload();
                    }
                })
            });

            /*************************************************REALIZAR UPLOAD DO EXCEL*********************************************************************/
            $("#arquivo_upload").on('change',function(e){
                var files = $('#arquivo_upload')[0].files;
                var load = $(".ajax_load");
                // let file = $(this).val();
                var fd = new FormData();
                fd.append('file',files[0]);
		        // fd.append('file',e.target.files[0]);	
                $.ajax({
                    url:"{{route('financeiro.sincronizar')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        load.fadeIn(200).css("display", "flex");
                        $('#uploadModal').modal('hide');
                    },
                    success:function(res) {
                       
                        if(res == "sucesso") {
                            load.fadeOut(200);
                            $('#uploadModal').modal('show');
                            $(".div_icone_arquivo_upload").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                            $("#arquivo_upload").val('').prop('disabled',true);

                        } else {

                        }
                        
                    }
                });
            });

            /*************************************************Atualizar Dados*********************************************************************/
            $(".atualizar_dados").on('click',function(){
                var load = $(".ajax_load");          
                
                $.ajax({
                    url:"{{route('financeiro.atualizar.dados')}}",
                    method:"POST",
                   
                    
                    beforeSend: function (res) {
                        load.fadeIn(200).css("display", "flex");
                        $('#uploadModal').modal('hide')
                        
                    },
                    success:function(res) { 
                        if (res == "sucesso") {
                            load.fadeOut(200);
                            $('#uploadModal').modal('show');
                            $(".div_icone_arquivo_upload").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                            $(".div_icone_atualizar_dados").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                            $(".atualizar_dados").removeClass('btn-warning').addClass('btn-secondary').prop('disabled',true);
                            $("#arquivo_upload").val('').prop('disabled',true);
                            //window.location.href = response.redirect;
                        } 
                    }
                });

                return false;
            });
            /*************************************************Sincronizar Dados*********************************************************************/
            $(".sincronizar_baixas").on('click',function(){
                var load = $(".ajax_load");       
                $.ajax({
                    url:"{{route('financeiro.sincronizar.baixas')}}",
                    method:"POST",
                    beforeSend: function (res) {
                        load.fadeIn(200).css("display", "flex");
                        $('#uploadModal').modal('hide')
                        
                    },
                    success:function(res) {
                        
                        if(res == "sucesso") {
                            window.location.reload();
                        } else {

                        }
                    }
                });
                return false;
            });

            /*****************************************************UPLOAD COLETIVO****************************************************************************** */
            $("#arquivo_upload_coletivo").on('change',function(e){
                var files = $('#arquivo_upload_coletivo')[0].files;
                
                var load = $(".ajax_load");
                
                var fd = new FormData();
                fd.append('file',files[0]);
		        
                $.ajax({
                    url:"{{route('financeiro.sincronizar.coletivo')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        load.fadeIn(200).css("display", "flex");
                        $('#uploadModalColetivo').modal('hide');
                    },
                    success:function(res) {
                       
                        if(res == "sucesso") {
                            load.fadeOut(200);

                            //$('#uploadModalColetivo').modal('show');
                            //$(".div_icone_arquivo_upload").removeClass('btn-danger').addClass('btn-success').html('<i class="far fa-smile-beam fa-lg"></i>');
                            //$("#arquivo_upload").val('').prop('disabled',true);

                        } else {

                        }
                        
                    }
                });
            })
            /*****************************************************FIM UPLOAD COLETIVO****************************************************************************** */




            var default_formulario = $('.coluna-right.aba_individual').html();

            $('#cpf_financeiro_coletivo_view').mask('000.000.000-00');
            $('#telefone_coletivo_view').mask('(00) 0000-0000');
            $("#dataBaixaIndividualModal").on('hidden.bs.modal', function (event) {
                $("#error_data_baixa_individual").html('');
            }); 
            $("#dataBaixaIndividualModal").on('shown.bs.modal', function (event) {
                $("#error_data_baixa_individual").html('');
            }); 

           $("body").on('click','.excluir_individual',function(){
                if($(this).attr('data-cliente-excluir-individual')) {
                    Swal.fire({
                        title: 'Você tem certeza que deseja realizar essa operação?',
                        showDenyButton: true,
                        confirmButtonText: 'Sim',
                        denyButtonText: `Cancelar`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let id_cliente = $(this).attr('data-cliente-excluir-individual');
                            $.ajax({
                                url:"{{route('financeiro.excluir.cliente.individual')}}",
                                method:"POST",
                                data:"id_cliente="+id_cliente,
                                success:function(res) {
                                    if(res != "error") {
                                        $(".individual_quantidade_em_analise").html(res.qtd_individual_em_analise);    
                                        $(".individual_quantidade_1_parcela").html(res.qtd_individual_01_parcela);
                                        $(".individual_quantidade_2_parcela").html(res.qtd_individual_02_parcela);
                                        $(".individual_quantidade_3_parcela").html(res.qtd_individual_03_parcela);
                                        $(".individual_quantidade_4_parcela").html(res.qtd_individual_04_parcela);
                                        $(".individual_quantidade_5_parcela").html(res.qtd_individual_05_parcela);
                                        $(".individual_quantidade_6_parcela").html(res.qtd_individual_06_parcela);
                                        $(".individual_quantidade_finalizado").html(res.qtd_individual_finalizado);
                                        $(".individual_quantidade_cancelado").html(res.qtd_individual_cancelado);
                                        table_individual.ajax.reload();
                                        limparFormularioIndividual();
                                    } else {
                                        Swal.fire('Opss', 'Erro ao excluir o cliente', 'error')  
                                    }
                                }
                            });    
                        } else if (result.isDenied) {
                            //
                        }
                    })
                }
           });
           
            

           $("body").on('click','.cancelar_individual',function(){
                $('#cancelarModal').modal('show')
           });




            // $(".mostrar_comissao").on('mouseenter',function(){   
            //     if($("#janela_atual").val() == "aba_individual") {
            //         var janela  = "aba_individual";
            //         var id_contrato = $('.next_individual').attr('data-contrato');
            //     } else if($("#janela_atual").val() == "aba_coletivo") {
            //         var janela  = "aba_coletivo";
            //         var id_contrato = $('.next').attr('data-contrato');
            //     } else {
            //         var janela  = "aba_empresarial";
            //         var id_contrato = $('.next_empresarial').attr('data-contrato');
            //     }
            //     if(janela && id_contrato) {
            //         $.ajax({
            //             url:"{{route('financeiro.ver.contrato')}}",
            //             method:"POST",
            //             data:"contrato_id="+id_contrato+"&janela="+janela,
            //             success:function(res) {
            //                 $("#container_mostrar_comissao").html(res).removeClass('ocultar');        
            //             }
            //         });
            //     }
            // }).on('mouseleave',function(){
            //     $("#container_mostrar_comissao").html('').addClass('ocultar'); 
            // });
            
            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }

            $(".list_abas li").on('click',function(){
                $('li').removeClass('ativo');
                $(this).addClass("ativo");
                let id = $(this).attr('data-id');
                $("#janela_atual").val(id);
                $("#janela_ativa").val(id);
                default_formulario = $('.coluna-right.'+id).html();
                $('.conteudo_abas main').addClass('ocultar');
                $('#'+id).removeClass('ocultar');
                $('.next').attr('data-cliente','');
                $('.next').attr('data-contrato','');
                $('tr').removeClass('textoforte');

                $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                table.ajax.url("{{ route('financeiro.coletivo.em_geral') }}").load();
                
                
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes') }}").load();
                
                $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.analise")}}').load();              
                
                $("#cliente_id_alvo").val('');
                $("#cliente_id_alvo_individual").val('');
                //limparFormularioIndividual();
                //limparFormulario();
                //limparEmpresarial();
                //adicionarReadonly();
                //adicionarReadonlyIndividual();
                $("#all_pendentes_individual").removeClass('textoforte-list');
                $("ul#listar li.coletivo").removeClass('textoforte-list');
                $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');

                //$("#aguardando_em_analise_empresarial").addClass('textoforte-list'); 
                //limparTudo();
            });

            

            $('.editar_btn_individual').on('click',function(){
                let params = $("#cliente").prop('readonly');
                if(!params) {
                    adicionarReadonlyIndividual();
                } else {
                    removeReadonlyIndividual();
                }
            });

            


            

            $("body").on('change','.editar_campo_individual',function(){
                let alvo = $(this).attr('id');
                let valor = $("#"+alvo).val();
                let id_cliente = $("#cliente_id_alvo_individual").val();
                $.ajax({
                    url:"{{route('financeiro.editar.individual.campoIndividualmente')}}",
                    method:"POST",
                    data:"alvo="+alvo+"&valor="+valor+"&id_cliente="+id_cliente,
                    success:function(res) {
                        table_individual.ajax.reload();
                    }
                });
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });               

            $("form[name='data_da_baixa_individual']").on('submit',function(){
                let id_cliente = $('.next_individual').attr('data-cliente');
                let id_contrato = $('.next_individual').attr('data-contrato');                
                $.ajax({
                    url:"{{route('financeiro.baixa.data.individual')}}",
                    method:"POST",
                    data: {
                        "id_cliente": id_cliente,
                        "id_contrato": id_contrato,
                        "data_baixa": $("#date_baixa_individual").val(),
                        "comissao_id": $("#comissao_id_baixa_individual").val()
                    },  
                    beforeSend:function() {
                        if($("#date_baixa_individual").val() == "") {
                            $("#error_data_baixa_individual").html('<p class="alert alert-danger">O campo data é campo obrigatório</p>');
                            return false;
                        } else {
                            $("#error_data_baixa_individual").html('');
                        }
                    },
                    success:function(res) {
                        $('#dataBaixaIndividualModal').modal('hide');
                        $(".individual_quantidade_em_analise").html(res.qtd_individual_em_analise);    
                        $(".individual_quantidade_1_parcela").html(res.qtd_individual_01_parcela);
                        $(".individual_quantidade_2_parcela").html(res.qtd_individual_02_parcela);
                        $(".individual_quantidade_3_parcela").html(res.qtd_individual_03_parcela);
                        $(".individual_quantidade_4_parcela").html(res.qtd_individual_04_parcela);
                        $(".individual_quantidade_5_parcela").html(res.qtd_individual_05_parcela);
                        $(".individual_quantidade_6_parcela").html(res.qtd_individual_06_parcela);
                        $(".individual_quantidade_finalizado").html(res.qtd_individual_finalizado);
                        $(".individual_quantidade_pendentes").html(res.qtd_individual_pendentes);
                        table_individual.ajax.reload();
                        limparFormulario();
                        $('#dataBaixaIndividualModal').modal('hide');
                        $('#date_baixa_individual').val('');
                        $('#error_data_baixa_individual').html('');
                    }
                });
                return false;
            });

            

            var tasemcarteirinha = $(".table_sem_carteirinha").DataTable({

                dom: '<"d-flex justify-content-between"<"#title_sem_carteirinha">ft><t><"d-flex justify-content-between"lp>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('financeiro.sem.carteirinha') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": true,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {
                        data:"created_at",name:"data",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        },
                    },
                    {
                        data:"codigo_externo",name:"codigo_externo"
                    },
                    {   
                        data:"clientes.user.name",name:"corretor",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let palavra = cellData.split(" ");
                            if(palavra.length >= 3) {
                                $(td).html(palavra[0]+" "+palavra[1]+"...")
                            }
                        }
                    },                 
                    {
                        data:"clientes.nome",name:"cliente",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 4) {
                                $(td).html(dados[0]+" "+dados[1]+" "+dados[2]+"...");
                            }   
                        }
                    },
                    {
                        data:"clientes.cpf",name:"cpf",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let cpf = cellData.substr(0,3)+"."+cellData.substr(3,3)+"."+cellData.substr(6,3)+"-"+cellData.substr(9,2);
                            $(td).html(cpf);
                        }
                    },
                    {data:"clientes.quantidade_vidas",name:"vidas"},
                    {
                        data:"clientes.id",name:"id",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var id = rowData.id;
                            $(td).html(`
                                <div class='text-center text-white'>
                                    <i class="fas fa-edit editar_carteirinha" data-id="${id}"></i>
                                </div>
                            `);
                            
                        }
                    }
                    
                ],
                "initComplete": function( settings, json ) {
                    $('#title_sem_carteirinha').html("<h4 style='font-size:1em;margin-top:10px;'>Sem Carteirinha</h4>");
                }

            });


            $("body").on('click','.editar_carteirinha',function(){
                let id = $(this).attr('data-id');
                $("#carteirinha_id_input").val(id);
                $('#carteirinhaModal').modal('show');
            });




            var taindividual = $(".listarindividual").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_individual">ft><t><"d-flex justify-content-between"lp>',
                order: [[0, 'desc']],
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('financeiro.individual.geralIndividualPendentes') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [10,20],
                //"ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
               
                columns: [
                    {data:"created_at",name:"created_at",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        },
                       
                    },
                    {
                        data:"codigo_externo",name:"codigo_externo"
                    },
                    {data:"clientes.user.name",name:"corretor",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            
                            let palavra = cellData.split(" ");
                            if(palavra.length >= 3) {
                                $(td).html(palavra[0]+" "+palavra[1]+"...")
                            }
                        }
                    },
                    
                    
                    {data:"clientes.nome",name:"cliente",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 4) {
                                $(td).html(dados[0]+" "+dados[1]+" "+dados[2]+"...");
                            }
                            
                        }
                    },


                    {data:"clientes.cpf",name:"cpf",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            
                            let cpf = cellData.substr(0,3)+"."+cellData.substr(3,3)+"."+cellData.substr(6,3)+"-"+cellData.substr(9,2);
                            $(td).html(cpf);
                        }
                    },

                    {data:"clientes.quantidade_vidas",name:"vidas",
                        
                    },                    
                    {
                        data:"valor_plano",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {data:"id",name:"vencimento",
                        "createdCell": function(td,cellData,rowData,row,col) {   
                            if(rowData.comissao.comissao_atual_financeiro == null) {
                                $(td).html("Finalizado");
                            } else {
                                $(td).html(rowData.comissao.comissao_atual_financeiro.data.split("-").reverse().join("/"));
                            }
                        }
                    },
                    {data:"financeiro.nome",name:"financeiro"},
                    {data:"financeiro.nome",name:"ver"}
                ],
                "columnDefs": [
                
                    {
                        "targets": 0,   
                        "width":"2%"
                    },
                    {
                        "targets": 1,   
                        "width":"5%",
                    },                 
                    {
                        "targets": 2,
                        "width":"13%",
                    },
                    {
                        "targets": 3,
                        "width":"20%",  
                    },
                    {
                        "targets": 4,
                        "width":"10%",      
                    },
                    {
                        "targets": 5,
                        "width":"5%",       
                    },
                    {
                        "targets":6,
                        "width":"8%",        
                    },
                    {
                        "targets":7,
                        "width":"5%",        
                    },
                    {
                        "targets": 8,
                        "width":"10%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(cellData == "Pagamento 1º Parcela") {
                                $(td).html("Pag. 1º Parcela");        
                            }
                            if(cellData == "Pagamento 2º Parcela") {
                                $(td).html("Pag. 2º Parcela");        
                            }
                            if(cellData == "Pagamento 3º Parcela") {
                                $(td).html("Pag. 3º Parcela");        
                            }
                            if(cellData == "Pagamento 4º Parcela") {
                                $(td).html("Pag. 4º Parcela");        
                            }
                            if(cellData == "Pagamento 5º Parcela") {
                                $(td).html("Pag. 5º Parcela");        
                            }
                            if(cellData == "Pagamento 6º Parcela") {
                                $(td).html("Pag. 6º Parcela");        
                            }
                        },
                    },
                    
                    {
                        "width":"2%",
                        "targets": 9,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            // console.log(cellData);
                            if(cellData == "Cancelado") {
                                var id = rowData.id;
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/admin/financeiro/cancelado/detalhes/${id}" class="text-white">  
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    </div>
                                `);

                                
                            } else {
                                var id = rowData.id;
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/admin/financeiro/detalhes/${id}" class="text-white">  
                                            <i class='fas fa-eye div_info'></i>
                                        </a>
                                    </div>
                                `);
                            }
                            
                        }
                    }
               ],
                "initComplete": function( settings, json ) {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Listagem</h4>");
                     this.api()
                       .columns([2])
                       .every(function () {
                            var column = this;
                            var selectUsuarioIndividual = $("#select_usuario_individual");
                            selectUsuarioIndividual.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }                               
                            });
                        })    

                    this.api()
                       .columns([0])
                       .every(function () {
                            var column = this;
                            var selectAno = $('#escolher_ano')
                            selectAno.on('change',function(){
                                
                                
                                var vals = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(vals != "todos") {
                                    //console.log(vals);
                                    column.search(vals ? '^' + vals + '$' : '', true, false).draw();    
                                } else {
                                    var vals = "";
                                    column.search(vals ? '^' + vals + '$' : '', true, false).draw();
                                }                               
                            });
                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    //var data = d;
                                    var dd = new Date(d);
                                    var ano = dd.getFullYear();
                                    
                                    selectAno.append('<option value="' + ano + '">' + ano + '</option>');
                                });

                        })    
                },
                "drawCallback": function( settings ) {
                    
                    // if(settings.iDraw >= 3 && settings.sTableId == "tabela_individual") {
                    //     var api = this.api();
                    //     var intVal = function (i) {
                    //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    //     };

                    //     total = api
                    //         .column(6)
                    //         .data()
                    //         .reduce(function (a, b) {
                    //             return intVal(a) + intVal(b);
                    //         }, 0);
                        
                    //     pageTotal = api
                    //         .column(6, { page: 'current' })
                    //         .data()
                    //         .reduce(function (a, b) {
                    //             return intVal(a) + intVal(b);
                    //         }, 0);
                    //     let total_cal = pageTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});     
                    //     let total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});    
                        
                    //     total_vidas = api.column(5,{ page: 'current' }).data().reduce(function (a, b) {
                    //         return intVal(a) + intVal(b);
                    //     }, 0);

                    //     total_linhas = api.column(5,{ page: 'current' }).data().count();   
                        
                    //     total_linhas_total_user = api.column(5).data().count();       

                    //     if($("#select_usuario_individual").val() != "todos") {
                    //         $(".total_por_page").html(total_cal);
                    //     } else {
                    //         $(".total_por_page").html(total_br);
                    //     }       

                    //     if($("#select_usuario_individual").val() != "todos") {
                    //         $(".total_por_vida").html(total_vidas);
                    //     } else {
                    //         //$(".total_por_page").html(total_br);
                    //     } 

                    //     if($("#select_usuario_individual").val() != "todos") {
                    //         $(".total_por_orcamento").html(total_linhas);
                    //     } else {
                    //         //$(".total_por_page").html(total_br);
                    //     } 
                        
                        
                    // }
                },





                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
 
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
 
                    // Total over all pages
                    total = api
                        .column(6,{search: 'applied'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    
                    total_vidas = api
                        .column(5,{search: 'applied'})
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0); 
                        
                    total_linhas = api
                        .column(5,{search: 'applied'})
                        .data()
                        .count();      

                         


                    let total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});   
                    // Total over this page
                   $(".total_por_page").html(total_br)
                   $(".total_por_vida").html(total_vidas);
                   $(".total_por_orcamento").html(total_linhas);
 
            // Update footer
                    //$(api.column(4).footer()).html('$' + pageTotal + ' ( $' + total + ' total)');
                }
            });

            






            $(".listardados").DataTable({
                
                dom: '<"d-flex justify-content-between"<"#title_coletivo_por_adesao_table"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('financeiro.coletivo.em_branco') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [500,1000,2000],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"created_at",name:"data"},
                    {data:"codigo_externo",name:"codigo_externo"},
                    {data:"clientes.user.name",name:"corretor",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            let dados = palavras.split(" ");
                            if(dados.length >= 4) {
                                $(td).html(dados[0]+" "+dados[1]+" "+dados[2]+"...");
                            }
                        }
                    },
                    {data:"clientes.nome",name:"cliente",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let palavra = cellData.split(" ");
                            if(palavra.length >= 3) {
                                $(td).html(palavra[0]+" "+palavra[1]+"...")
                            }
                        }
                    },
                    {data:"administradora.nome",name:"administradora"},
                    {data:"clientes.cpf",name:"cpf"},
                    {data:"clientes.quantidade_vidas",name:"vidas"},
                    {data:"valor_plano",name:"valor_plano",render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')},
                    {data:"comissao.comissao_atual_financeiro",name:"Vencimento"},
                    {data:"financeiro.nome",name:"administradora"},
                    {data:"financeiro.nome",name:"detalhes"}  
                ],
                "columnDefs": [
                    {
                        /**Data*/
                        "targets": 0,
                        "width":"2%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        }
                    },
                    /**Orçamento*/
                    {
                        "targets": 1,
                        "width":"5%",
                        
                    },
                    /** Corretor */
                    {
                        "targets": 2,
                        "width":"13%",
                        
                    },
                    /*Cliente*/
                    {
                        "targets": 3,
                        "width":"20"
                    },
                    /*Administradora*/
                    {
                        "targets": 4,
                        "width":"10%"                
                    },
                    /*CPF */
                    {
                        "targets": 5,
                        "width":"10%"
                    },
                    /*Vidas */
                    {
                        "targets": 6,
                        "width":"5%"
                        
                    },
                    /*Valor */
                    {
                        "targets": 7,
                        "width":"8%"
                    },
                    /*Vencimento*/
                    {
                        "targets": 8,
                        "width":"5%",        
                        "createdCell": function (td, cellData, rowData, row, col) {
                            
                            if(rowData.comissao.comissao_atual_financeiro == null) {

                                if(rowData.comissao.comissao_atual_last && rowData.comissao.comissao_atual_last.parcela == 7) {
                                    let alvo_final =  rowData.comissao.comissao_atual_last.data.split("-").reverse().join("/");
                                    $(td).html(alvo_final);
                                }


                            } else {
                                let alvo = cellData.data.split("-").reverse().join("/");
                                $(td).html(alvo);
                            }
                            
                        },
                    },
                    /*Status*/
                    {
                        "targets": 9,
                        "width":"10%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            console.log(rowData);

                            if(rowData.financeiro_id == 1 && rowData.financeiro.nome == "Em Análise" && rowData.comissao.comissao_atual_last == null) {
                                $(td).html("Em Análise");
                            } else if(rowData.financeiro_id == 12 && rowData.financeiro.nome == "Cancelado" && rowData.comissao.comissao_atual_last == null) {
                                $(td).html("Cancelado");
                            } else if(rowData.financeiro_id == 2 && rowData.financeiro.nome == "Emissão Boleto" && rowData.comissao.comissao_atual_last == null) {
                                $(td).html("Emissão Boleto");
                            } else if(rowData.financeiro_id == 3 && rowData.financeiro.nome == "Pagamento Adesão" && rowData.comissao.comissao_atual_last == null) {
                                $(td).html("Pag. Adesão");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_last.parcela == 1) {
                                $(td).html("Pag. Vigência");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_last.parcela == 2) {
                                $(td).html("Pag. 2º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_last.parcela == 3) {
                                $(td).html("Pag. 3º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_last.parcela == 4) {
                                $(td).html("Pag. 4º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_last.parcela == 5) {
                                $(td).html("Pag. 5º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_last.parcela == 6) {
                                $(td).html("Pag. 6º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro == null && rowData.comissao.comissao_atual_last.parcela == 7) {
                                $(td).html("Pago");
                            } else {
                                $(td).html("Error");
                            }                            
                        },
                    },
                    /*Detalhes*/
                    {
                        "targets": 10,
                        "width":"2%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            
                                var id = rowData.id;
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/admin/financeiro/detalhes/coletivo/${id}" class="text-white">  
                                            <i class='fas fa-eye div_info'></i>
                                        </a>
                                    </div>
                                `);
                            
                            
                        },
                    },

                ],              
                "initComplete": function( settings, json ) {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                    this.api()
                       .columns([2])
                       .every(function () {
                            var column = this;
                            var selectUsuario = $("#select_usuario");
                            selectUsuario.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }             
                            });
                        })
                        this.api()
                       .columns([4])
                       .every(function () {
                            var column = this;
                            var selectAdministradora = $("#select_coletivo_administradoras");
                            selectAdministradora.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }             
                            });
                        })    
                    //console.log(settings);
                    
                },
                "drawCallback":function(settings) {
                    
                    
                    if(settings.iDraw >= 3 && settings.sTableId == "tabela_coletivo") {
                        var intVal = function (i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };
                        var api = this.api();
                        total_vidas = api.column(6,{ page: 'current' }).data().reduce(function (a, b) {
	                        return intVal(a) + intVal(b);
                        }, 0);

                        total_linhas = api.column(0,{ page: 'current' }).data().count();
                        
                        total = api.column(7,{ page: 'current' }).data().reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        let total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});


                        $(".total_por_vida_coletivo").html(total_vidas);
                        $(".total_por_orcamento_coletivo").html(total_linhas);
                        $(".total_por_page_coletivo").html(total_br);

                    }
                },    

                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
 
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
 
                    // Total over all pages
                    total = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    
                    total_vidas = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0); 
                        
                    total_linhas = api
                        .column(5)
                        .data()
                        .count();         


                    let total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});   
                    // Total over this page
                        $(".total_por_vida_coletivo").html(total_vidas);
                        $(".total_por_orcamento_coletivo").html(total_linhas);
                        $(".total_por_page_coletivo").html(total_br);
 
            // Update footer
                    //$(api.column(4).footer()).html('$' + pageTotal + ' ( $' + total + ' total)');
                }


                    //if(settings.json != null && settings.json != "") {
                        //$("#select_usuario").html('<option value="todos" class="text-center">---Escolher Corretor---</option>');
                        
                    //}
                    // $("#select_usuario").html('')
                    // //if(settings.iDraw == 2) {

                    //     this.api()
                    //    .columns([2])
                    //    .every(function () {
                    //         var column = this;
                    //         var selectUsuario = $("#select_usuario");
                    //         selectUsuario.on('change',function(){
                    //             var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    //             if(val != "todos") {
                    //                 column.search(val ? '^' + val + '$' : '', true, false).draw();    
                    //             } else {
                    //                 var val = "";
                    //                 column.search(val ? '^' + val + '$' : '', true, false).draw();
                    //             }             
                    //         });

                    //         column.data().unique().sort().each(function (d, j) {
                    //             selectUsuario.append('<option value="' + d + '">' + d + '</option>');
                    //         });
                    //    })



                    //}

                    //$("#select_usuario").html('');
                    
                //     this.api()
                //        .columns([3])
                //        .every(function () {
                //             var column = this;
                //             var selectAdministradora = $("#select_coletivo");
                //             selectAdministradora.on('change',function(){
                //                 var val = $.fn.dataTable.util.escapeRegex($(this).val());
                //                 if(val != "todos") {
                //                     column.search(val ? '^' + val + '$' : '', true, false).draw();    
                //                 } else {
                //                     var val = "";
                //                     column.search(val ? '^' + val + '$' : '', true, false).draw();
                //                 }
                //             });
                //             column.data().unique().sort().each(function (d, j) {
                //                 selectAdministradora.append('<option value="' + d + '">' + d + '</option>');
                //             });
                //        })
            
            });

            var table = $('#tabela_coletivo').DataTable();
            $('#tabela_coletivo').on('click', 'tbody tr', function () {
                table.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
            });

            $("#finalizado_corretor_coletivo").on('click',function(){

                table.ajax.url("{{ route('financeiro.coletivo.finalizado')  }}").load();
                $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                $("ul#listar li.coletivo").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
            });

            $("#select_usuario_individual").on('change',function(){
                    let mes = $("#mudar_mes_table").val();
                    let id = $('option:selected', this).attr('data-id');
                
                    $.ajax({
                    url:"{{route('financeiro.corretor.quantidade')}}",
                    method:"POST",
                    data:"id="+id,
                        success:function(res) {
                            $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                            //table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes') }}").load();
                            $("ul#listar_individual li.individual").removeClass('textoforte-list');
                            $("#atrasado_corretor").removeClass('textoforte-list');
                            $("#all_pendentes_individual").addClass('textoforte-list');
                            $(".individual_quantidade_pendentes").html(res.qtd_clientes);
                            $(".individual_quantidade_1_parcela").html(res.qtd_individual_parcela_01);
                            $(".individual_quantidade_2_parcela").html(res.qtd_individual_parcela_02);
                            $(".individual_quantidade_3_parcela").html(res.qtd_individual_parcela_03);
                            $(".individual_quantidade_4_parcela").html(res.qtd_individual_parcela_04);
                            $(".individual_quantidade_5_parcela").html(res.qtd_individual_parcela_05);
                            $(".individual_quantidade_6_parcela").html(res.qtd_individual_parcela_06);
                            $(".individual_quantidade_cancelado").html(res.qtd_individual_cancelado);
                            // $(".total_por_vida").html(res.qtd_vidas);
                            // $(".total_por_orcamento").html(res.qtd_clientes);
                            $(".individual_quantidade_atrasado").html(res.qtd_individual_atrasado);
                        }
                    });
            });




            $('body').on('change','#data_vigencia_coletivo_view',function(){
                let valor = $(this).val();
                let cliente = $("#cliente_id_alvo").val();
                $.ajax({
                    url:"{{route('financeiro.mudarVigenciaColetivo')}}",
                    method:"POST",
                    data:"data="+valor+"&cliente_id="+cliente,
                });
            });

            var table_individual = $('#tabela_individual').DataTable();
            $('#tabela_individual').on('click', 'tbody tr', function () {
                table_individual.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
            });    


            $("form[name='colocar_carteirinha']").on('submit',function(){
                var load = $(".ajax_load");
                $.ajax({
                    url:"{{route('cliente.atualizar.carteirinha')}}",
                    method:"POST",
                    data:$(this).serialize(),
                    beforeSend: function () {
                        load.fadeIn(100).css("display", "flex");
                        $('#carteirinhaModal').modal('hide');
                    },
                    success:function(res) {                        
                        if(res == "error") {
                            load.fadeOut(300);
                            $('#carteirinhaModal').modal('show');
                            $("#carteirinha_error").html('<p class="alert alert-danger text-center">Carteirinha inválida, tente outra =/</p>')
                        } else {
                            load.fadeOut(300);
                            $('#carteirinhaModal').modal('hide');
                            $(".individual_quantidade_pendentes").html(res.qtd_clientes);
                            $(".individual_quantidade_1_parcela").html(res.qtd_individual_parcela_01);
                            $(".individual_quantidade_2_parcela").html(res.qtd_individual_parcela_02);
                            $(".individual_quantidade_3_parcela").html(res.qtd_individual_parcela_03);
                            $(".individual_quantidade_4_parcela").html(res.qtd_individual_parcela_04);
                            $(".individual_quantidade_5_parcela").html(res.qtd_individual_parcela_05);
                            $(".individual_quantidade_6_parcela").html(res.qtd_individual_parcela_06);
                            $(".individual_quantidade_cancelado").html(res.qtd_individual_cancelado);
                            // $(".total_por_vida").html(res.qtd_vidas);
                            // $(".total_por_orcamento").html(res.qtd_clientes);
                            // $(".individual_quantidade_atrasado").html(res.qtd_individual_atrasado);
                            tasemcarteirinha.ajax.reload();
                        }
                    }
                });
                return false;
            });




            $(".listarempresarial").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_empresarial">ft><t><"d-flex justify-content-between"lp>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('contratos.listarEmpresarial.analise') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [200,250,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                
                columns: [
                    {data:"created_at",name:"created_at"},
                    {data:"codigo_externo",name:"codigo_externo"},
                    {data:"usuario",name:"usuario"},
                    {data:"responsavel",name:"responsavel"},
                    {data:"cnpj",name:"cnpj"},
                    {data:"quantidade_vidas",name:"vidas"},
                    
                    {data:"valor_plano",name:"valor_plano",
                        render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')
                    },
                    {data:"comissao.comissao_atual_financeiro",name:"vencimento",
                        "createdCell": function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                if(rowData.financeiro_id == 11) {
                                    $(td).html('Finalizado');
                                } else if(rowData.financeiro_id == 12) {
                                    $(td).html('Cancelado');
                                } else  {
                                    let alvo = rowData.comissao.comissao_atual_last.data.split("-").reverse().join("/");
                                    $(td).html(alvo);    
                                }
                            } else {
                                let alvo = cellData.data.split("-").reverse().join("/");
                                $(td).html(alvo);
                            }
                        }
                    },
                    {data:"comissao.comissao_atual_financeiro",name:"financeiro"},
                    {data:"razao_social",name:"razao_social"},
                ],
                "columnDefs": [
                    // <th>Data</th>
                    {
                        "targets": 0,
                        "width":"5%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        }
                    },

                    {
                        "targets": 8,
                        "width":"10%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(rowData.financeiro_id == 1 && rowData.financeiro.nome == "Em Análise" && rowData.comissao.comissao_atual_last == null) {
                                $(td).html("Em Análise");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_financeiro.parcela == 1) {
                                $(td).html("Pag. 1º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_financeiro.parcela == 2) {
                                $(td).html("Pag. 2º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_financeiro.parcela == 3) {
                                $(td).html("Pag. 3º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_financeiro.parcela == 4) {
                                $(td).html("Pag. 4º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_financeiro.parcela == 5) {
                                $(td).html("Pag. 5º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro != null && rowData.comissao.comissao_atual_financeiro.parcela == 6) {
                                $(td).html("Pag. 6º Parcela");
                            } else if(rowData.comissao.comissao_atual_financeiro == null && rowData.comissao.comissao_atual_last.parcela == 6) {
                                $(td).html("Pagou");
                            } else {
                                $(td).html("Error");
                            }                            
                        },
                    },
                    

                    {
                        "targets": 9,
                        "width":"2%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var id = rowData.id;
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/admin/financeiro/detalhes/empresarial/${id}" class="text-white">  
                                            <i class='fas fa-eye div_info'></i>
                                        </a>
                                    </div>
                                `);
                        },
                    },
                    // {
                    //     "targets": 8,
                    //     "width":"10%",
                    //     "createdCell": function (td, cellData, rowData, row, col) {
                    //         console.log(rowData);
                    //         if(cellData == "Pagamento 1º Parcela") {
                    //             $(td).html("Pag. 1º Parcela");        
                    //         }
                    //         if(cellData == "Pagamento 2º Parcela") {
                    //             $(td).html("Pag. 2º Parcela");        
                    //         }
                    //         if(cellData == "Pagamento 3º Parcela") {
                    //             $(td).html("Pag. 3º Parcela");        
                    //         }
                    //         if(cellData == "Pagamento 4º Parcela") {
                    //             $(td).html("Pag. 4º Parcela");        
                    //         }
                    //         if(cellData == "Pagamento 5º Parcela") {
                    //             $(td).html("Pag. 5º Parcela");        
                    //         }
                    //         if(cellData == "Pagamento 6º Parcela") {
                    //             $(td).html("Pag. 6º Parcela");        
                    //         }
                    //     },
                    // },
                    // // <th>Corretor</th>
                    // {
                    //     "targets": 1,
                    //     "width":"25%"
                    // },
                    // // <th>Cliente</th>
                    // {
                    //     "targets": 2,
                    //     "width":"25%"
                    // },
                    // // <th>Razão Social</th>
                    // {
                    //     "targets": 3,
                    //     "width":"20%",
                        
                    // },
                    // // <th>Valor</th>
                    // {
                    //     "targets": 4,
                    //     "width":"0%",
                    //     "createdCell": function (td, cellData, rowData, row, col) {
                    //         let formatado = Number(cellData).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    //         // let datas = cellData.split("T")[0]
                    //         // let alvo = datas.split("-").reverse().join("/")
                    //         $(td).html(formatado);    
                    //     }
                        
                    // },
                    // // <th>Vencimento</th>
                    // {
                    //     "targets": 5,
                    //     "createdCell": function (td, cellData, rowData, row, col) {
                    //         let alvo = cellData.split("-").reverse().join("/")
                    //         $(td).html(alvo);
                    //     }
                        
                    // },
                    // // <th>Ver</th> 
                    // {
                    //     "targets": 6,
                    //     "createdCell": function (td, cellData, rowData, row, col) {
                    //         $(td).html("<div class='text-center'><i class='fas fa-eye div_info' data-id='"+rowData.id+"'></i></div>");
                    //     }
                    // }
               ],
                "initComplete": function( settings, json ) {
                    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Em Analise</h4>");
                    this.api()
                       .columns([2])
                       .every(function () {
                            var column = this;
                            var selectUsuario = $("#select_usuario");
                            selectUsuario.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }             
                            });
                        })
                        this.api()
                       .columns([4])
                       .every(function () {
                            var column = this;
                            var selectAdministradora = $("#select_coletivo_administradoras");
                            selectAdministradora.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                }             
                            });
                        })    
                },
                "drawCallback":function(settings) {
                    if(settings.iDraw >= 3 && settings.sTableId == "tabela_empresarial") {
                        var intVal = function (i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };
                        var api = this.api();
                        total_vidas_empresa = api.column(5,{page:'current'}).data().reduce(function (a, b) {
	                        return intVal(a) + intVal(b);
                        }, 0);
                        total_linhas_empresa = api.column(0,{page:'current'}).data().count();                        
                        total_empresa = api.column(6,{page:'current'}).data().reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        let total_br_empresa = total_empresa.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                        $(".total_por_orcamento_empresarial").html(total_linhas_empresa);
                        $(".total_por_vida_empresarial").html(total_vidas_empresa);
                        $(".total_por_page_empresarial").html(total_br_empresa);
                    }
                }
            });

            var tableempresarial = $('#tabela_empresarial').DataTable();
            $('#tabela_empresarial').on('click', 'tbody tr', function () {
                tableempresarial.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
                
            });         

            

            // $("body").on('mouseover','.div_info',function(){
            //    let contrato = $(this).attr('data-id');
            //    let janela_ativa = $('#janela_ativa').val(); 
            //    $.ajax({
            //         url:"{{route('contratos.info')}}",
            //         data:"contrato="+contrato,
            //         method:"POST",
            //         success:function(res) {
            //             $('.coluna-right.'+janela_ativa).html(res);
            //             //$('.container_div_info').html(res);
            //         }
            //     });
            //     $('.container_div_info').toggle();
            //     return false;
            // });

            // $("body").on('mouseout','.div_info',function(){
            //     let janela_ativa = $('#janela_ativa').val();
            //     $(".coluna-right."+janela_ativa).html(default_formulario);
            // });

            $("#list_individual_begin").on('click',function(){    
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes') }}").load();
            });

            $("#list_coletivo_begin").on('click',function(){
                $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                table.ajax.url("{{ route('financeiro.coletivo.em_geral') }}").load();
                $("ul#listar li.coletivo").removeClass('textoforte-list');
                $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
            })

            $("#list_empresarial_begin").on('click',function(){
                $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
	            $("#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
	            tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.listarContratoEmpresaPendentes")}}').load();
            });



            $("body").on('click','.next_individual',function(){
                if($(this).attr('data-cliente') && $(this).attr('data-contrato')) {
                    
                    let id_cliente = $(this).attr('data-cliente');
                    let id_contrato = $(this).attr('data-contrato');

                    $.ajax({
                        url:"{{route('financeiro.mudarStatusIndividual')}}",
                        data:"id_cliente="+id_cliente+"&id_contrato="+id_contrato,
                        method:"POST",
                        success:function(res) {
                            
                            if(res == "abrir_modal_individual") {
                                //$('#dataBaixaModal').modal('show');                      
                                $("#dataBaixaIndividualModal").modal('show');
                            } else {
                                
                                $(".individual_quantidade_em_analise").html(res.qtd_individual_em_analise);    
                                $(".individual_quantidade_1_parcela").html(res.qtd_individual_01_parcela);
                                $(".individual_quantidade_2_parcela").html(res.qtd_individual_02_parcela);
                                $(".individual_quantidade_3_parcela").html(res.qtd_individual_03_parcela);
                                $(".individual_quantidade_4_parcela").html(res.qtd_individual_04_parcela);
                                $(".individual_quantidade_5_parcela").html(res.qtd_individual_05_parcela);
                                $(".individual_quantidade_6_parcela").html(res.qtd_individual_06_parcela);
                                $(".individual_quantidade_finalizado").html(res.qtd_individual_finalizado);
                                
                                taindividual.ajax.reload();
                               
                            }
                        }
                    });
                } else {                     
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        type: "error",
                        width: '400px',
                        html: "Tem que selecionar um item da tabela, para mudar de status"
                    })
                }
            });


            


            


            

            $("ul#listar li.coletivo").on('click',function(){
                let id_lista = $(this).attr('id');
                
                if(id_lista == "em_analise_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.em_analise') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 excluir_coletivo">Excluir</button>'+
                        '<button class="btn btn-success w-50 next">Conferido</button>'
                    );   
                    $(".container_edit").removeClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');                   
                    

                } else if(id_lista == "emissao_boleto_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Emissão Boleto</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.emissao_boleto') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 excluir_coletivo">Excluir</button>'+
                        '<button class="btn btn-success w-50 next">Emitiu Boleto</button>'
                    );                      
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                   
                } else if(id_lista == "pagamento_adesao_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento Adesão</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_adesao') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_adesao next">Pagou Adesão</button>'
                    );
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    

                } else if(id_lista == "pagamento_vigencia_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento Vigência</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_vigencia') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_vegencia next">Pagou Vigência</button>'
                    );
                    $(".container_edit").addClass('ocultar');                   
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                   
                } else if(id_lista == "pagamento_segunda_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 2º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_segunda_parcela') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_segunda_parcela next">2º Parcela Paga</button>'
                    );
                    $(".container_edit").addClass('ocultar');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "pagamento_terceira_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 3º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_terceira_parcela') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_terceira_parcela next">3º Parcela Paga</button>'
                    );
                    $(".container_edit").addClass('ocultar');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "pagamento_quarta_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 4º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_quarta_parcela') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_quarta_parcela next">4º Parcela Paga</button>'
                    );
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                
                } else if(id_lista == "pagamento_quinta_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 5º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_quinta_parcela') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_quinta_parcela next">5º Parcela Paga</button>'
                    );
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "pagamento_sexta_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 6º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_sexta_parcela') }}").load();
                    $('.buttons').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>'+
                        '<button class="btn btn-success w-50 pagamento_sexta_parcela next">6º Parcela Paga</button>'
                    );
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#finalizado_corretor_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else {

                }
            });  

            $("#all_pendentes_individual").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes') }}").load();
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("#atrasado_corretor").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
            });
            
            

            


            $("ul#listar_individual li.individual").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "aguardando_em_analise_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.em_analise') }}").load();
                    // $('.button_individual').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 excluir_individual">Excluir</button>'+
                    //     '<button class="btn btn-success w-50 next_individual">Conferido</button>'
                    // );    
                    $("#atrasado_corretor").removeClass('textoforte-list');                  
                    $(".container_edit").removeClass('ocultar')
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "aguardando_pagamento_1_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 1º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_primeira_parcela') }}").load();
                    $("#atrasado_corretor").removeClass('textoforte-list');
                    // $('.button_individual').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_individual">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 emissao_boleto next_individual">1º Parcela Paga</button>'
                    // );                      
                    $(".container_edit").addClass('ocultar')
                    //adicionarReadonly();
                    $("#atrasado_corretor").removeClass('textoforte-list');
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "aguardando_pagamento_2_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 2º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_segunda_parcela') }}").load();
                    // $('.button_individual').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_individual">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 pagamento_adesao next_individual">2º Parcela Paga</button>'
                    // );
                    $("#atrasado_corretor").removeClass('textoforte-list');
                    $(".container_edit").addClass('ocultar')
                    //adicionarReadonly();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $("#finalizado_corretor").removeClass('textoforte-list');
                    $("#cancelado_individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "aguardando_pagamento_3_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 3º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_terceira_parcela') }}").load();
                    // $('.button_individual').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_individual">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 pagamento_vegencia next_individual">3º Parcela Paga</button>'
                    // );
                    $("#atrasado_corretor").removeClass('textoforte-list');
                    $(".container_edit").addClass('ocultar');
                    $("#cancelado_individual").removeClass('textoforte-list');
                    //adicionarReadonly();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $("#finalizado_corretor").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else if(id_lista == "aguardando_pagamento_4_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 4º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_quarta_parcela') }}").load();
                    // $('.button_individual').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_individual">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 pagamento_segunda_parcela next_individual">4º Parcela Paga</button>'
                    // );
                    $("#atrasado_corretor").removeClass('textoforte-list');
                    $(".container_edit").addClass('ocultar')
                    //adicionarReadonly();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $("#finalizado_corretor").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $("#cancelado_individual").removeClass('textoforte-list');
                    
                } else if(id_lista == "aguardando_pagamento_5_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 5º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_quinta_parcela') }}").load();
                    // $('.button_individual').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_individual">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 pagamento_terceira_parcela next_individual">5º Parcela Paga</button>'
                    // );
                    $(".container_edit").addClass('ocultar')
                    //adicionarReadonly();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $("#finalizado_corretor").removeClass('textoforte-list');
                    $("#cancelado_individual").removeClass('textoforte-list');
                    $("#atrasado_corretor").removeClass('textoforte-list');

                    $(this).addClass('textoforte-list');
                    
                } else {

                }
            });  

            $("#aguardando_pagamento_6_parcela_individual").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 6º Parcela</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.pagamento_sexta_parcela') }}").load();
                // $('.button_individual').empty().html(
                //     '<button class="btn btn-danger w-50 mr-2 cancelar_individual">Cancelar</button>'+
                //     '<button class="btn btn-success w-50 pagamento_quarta_parcela next_individual">6º Parcela Paga</button>'
                // );
                $(".container_edit").addClass('ocultar')
                //adicionarReadonly();
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("#all_pendentes_individual").removeClass('textoforte-list');
                $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                $("#cancelado_individual").removeClass('textoforte-list');
                $("#atrasado_corretor").removeClass('textoforte-list');
                $("#finalizado_corretor").addClass('textoforte-list');
                
            }); 



            $("ul#grupo_finalizados li.coletivo").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "finalizado_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.finalizado') }}").load();
                    $('.buttons').empty().html();
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                   
                } else if(id_lista == "cancelado_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.cancelado') }}").load();
                    $('.buttons').empty().html();
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_finalizados li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else {

                }
            });

            $("#atrasado_corretor").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Atrasado</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.atrasado') }}").load();
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("#all_pendentes_individual").removeClass('textoforte-list');
                $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
                $("#aguardando_pagamento_6_parcela_individual").removeClass('textoforte-list');
                $("#finalizado_corretor").removeClass('textoforte-list');
                $("#cancelado_individual").removeClass('textoforte-list');

            });

            $("ul#grupo_finalizados_individual li.individual").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "finalizado_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.finalizado') }}").load();
                    $('.button_individual').empty().html('');
                    $(".container_edit").addClass('ocultar');
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $("#atrasado_corretor").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else {
                }
            });

            $("#cancelado_individual").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.cancelado') }}").load();
                $('.button_individual').empty().html('');
                $(".container_edit").addClass('ocultar');
                $("#atrasado_corretor").removeClass('textoforte-list');
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("#aguardando_pagamento_6_parcela_individual").removeClass('textoforte-list');
                $("#all_pendentes_individual").removeClass('textoforte-list');
                $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                $("#finalizado_corretor").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
                
            });







            $("ul#grupo_finalizados_empresarial li.empresarial").on('click',function(){
                let id_lista = $(this).attr('id');                
                if(id_lista == "aguardando_finalizado_empresarial") {
                    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.finalizado') }}").load();
                    $('.button_individual').empty().html('');
                    $(".container_edit").addClass('ocultar');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                   
                } else if(id_lista == "aguardando_cancelado_empresarial") {
                    $('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.cancelado') }}").load();
                    $('.button_individual').empty().html('');
                    $(".container_edit").addClass('ocultar');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    
                } else {


                }
            });

            $("ul#listar_empresarial li.empresarial").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "aguardando_em_analise_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                    $('.button_empresarial').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 excluir_empresarial">Excluir</button>'+
                        '<button class="btn btn-success w-50 next_empresarial">Conferido</button>'
                    );
                    //$("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');    
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.analise")}}').load();
                } else if(id_lista == "aguardando_pagamento_1_parcela_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 1º Parcela</h4>");
                    // $('.button_empresarial').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_empresarial">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 emissao_boleto next_empresarial">1º Parcela Paga</button>'
                    // );                      
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');  
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.primeiraparcela")}}').load();
                } else if(id_lista == "aguardando_pagamento_2_parcela_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 2º Parcela</h4>");
                    // $('.button_empresarial').empty().html(
                    //     '<button class="btn btn-danger w-50 mr-2 cancelar_empresarial">Cancelar</button>'+
                    //     '<button class="btn btn-success w-50 emissao_boleto next_empresarial">2º Parcela Paga</button>'
                    // );                     
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');        
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.segundaparcela")}}').load();
                } else if(id_lista == "aguardando_pagamento_3_parcela_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 3º Parcela</h4>");
                    $('.button_empresarial').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar_empresarial">Cancelar</button>'+
                        '<button class="btn btn-success w-50 emissao_boleto next_empresarial">3º Parcela Paga</button>'
                    );                       
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');        
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.terceiraparcela")}}').load();
                } else if(id_lista == "aguardando_pagamento_4_parcela_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 4º Parcela</h4>");
                    $('.button_empresarial').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar_empresarial">Cancelar</button>'+
                        '<button class="btn btn-success w-50 emissao_boleto next_empresarial">4º Parcela Paga</button>'
                    );  
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.quartaparcela")}}').load();
                } else if(id_lista == "aguardando_pagamento_5_parcela_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 5º Parcela</h4>");
                    $('.button_empresarial').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar_empresarial">Cancelar</button>'+
                        '<button class="btn btn-success w-50 emissao_boleto next_empresarial">5º Parcela Paga</button>'
                    );                      
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.quintaparcela")}}').load();
                } else if(id_lista == "aguardando_pagamento_6_parcela_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 6º Parcela</h4>");
                    $('.button_empresarial').empty().html(
                        '<button class="btn btn-danger w-50 mr-2 cancelar_empresarial">Cancelar</button>'+
                        '<button class="btn btn-success w-50 emissao_boleto next_empresarial">6º Parcela Paga</button>'
                    );                
                    $("ul#listar_empresarial li.empresarial").removeClass('textoforte-list');
                    $("ul#grupo_finalizados_empresarial li.empresarial").removeClass('textoforte-list');
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.sextaparcela")}}').load();
                } else if(id_lista == "aguardando_finalizado_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                    $('.button_empresarial').empty().html('');  
                    $("#all_pendentes_empresarial").removeClass('textoforte-list');              
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.finalizado")}}').load();
                } else if(id_lista == "aguardando_cancelado_empresarial") {
                    $("#title_empresarial").html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
                    tableempresarial.ajax.url('{{route("contratos.listarEmpresarial.cancelado")}}').load();
                } else {

                }
            });
            var contar = 0;
           
            
            $(".div_info").on('click',function(){
                let contrato = $(this).attr('data-id');
                $.ajax({
                    url:"{{route('contratos.info')}}",
                    data:"contrato="+contrato,
                    method:"POST",
                    success:function(res) {
                        $('.container_div_info').html(res);
                    }
                });
                $('.container_div_info').toggle();
                return false;
            });

            if($("#janela_atual").val() == "aba_coletivo") {

                table.on( 'xhr', function (e, settings, json) {
                    
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Contratos</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.em_geral') }}").load();    
                    table.off( 'xhr' );
                });   
            } 




           





        });
    </script>
@stop


@section('css')
    <style>
        .ajax_load {display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;}
        .ajax_load_box{margin:auto;text-align:center;color:#fff;font-weight:var(700);text-shadow:1px 1px 1px rgba(0,0,0,.5)}
        .ajax_load_box_circle{border:16px solid #e3e3e3;border-top:16px solid #61DDBC;border-radius:50%;margin:auto;width:80px;height:80px;-webkit-animation:spin 1.2s linear infinite;-o-animation:spin 1.2s linear infinite;animation:spin 1.2s linear infinite}
        @-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
        #container_mostrar_comissao {width:439px;height:555px;background-color: #123449;position: absolute;right:5px;border-radius: 5px;}
        .container_edit {display:flex;justify-content:end;}
        .ativo {background-color:#FFF !important;color: #000 !important;}
        .ocultar {display: none;}
        .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 4px 0;padding: 0;}
        .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
        .list_abas li:hover {cursor: pointer;}    
        .list_abas li:nth-of-type(2) {margin: 0 1%;}
        .list_abas li:nth-of-type(4) {margin-left:1%;}
        .textoforte {background-color:rgba(255,255,255,0.5) !important;color:black;}
        .textoforte-list {background-color:rgba(255,255,255,0.5);color:white;}
        .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;}
        .valores-acomodacao {background-color:#123449;color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;}
        .valores-acomodacao:hover {cursor:pointer;box-shadow: none;}
        .table thead tr {background-color:#123449;color: white;}
        .destaque {border:4px solid rgba(36,125,157);}
        #coluna_direita {flex-basis:10%;background-color:#123449;border-radius: 5px;}
        #coluna_direita ul {list-style: none;margin: 0;padding: 0;}
        #coluna_direita li {color:#FFF;}
        .coluna-right {flex-basis:30%;flex-wrap: wrap;border-radius:5px;height:720px;}
        .coluna-right.aba_individual {flex-basis:30%;flex-wrap: wrap;border-radius:5px;height:1000px;}



        /* .container_div_info {background-color:rgba(0,0,0,1);position:absolute;width:500px;right:0px;top:57px;min-height: 700px;display: none;z-index: 1;color: #FFF;} */
        .container_div_info {display:flex;position:absolute;flex-basis:30%;right:0px;top:57px;display: none;z-index: 1;color: #FFF;}
        #padrao {width:50px;background-color:#FFF;color:#000;}
        .buttons {display: flex;}
        .button_individual {display:flex;}
        .button_empresarial {display: flex;}

        

        
        
        .dt-right {text-align: right !important;}
        .dt-center {text-align: center !important;}
        .estilizar_pagination .pagination {font-size: 0.8em !important;color:#FFF;}
        .estilizar_pagination .pagination li {height:10px;color:#FFF;}
        .por_pagina {font-size: 12px !important;color:#FFF;}
        .por_pagina #tabela_mes_atual_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina #tabela_mes_diferente_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina select {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_atual_previous {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_atual_next {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_diferente_previous {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_diferente_next {color:#FFF !important;}
        #tabela_individual_filter input[type='search'] {background-color: #FFF !important;}


        
        
        #tabela_coletivo_filter input[type='search'] {background-color: #FFF !important;}

        #tabela_empresarial_filter input[type='search'] {background-color: #FFF !important;}

        th { font-size: 0.9em !important; }
        td { font-size: 0.8em !important; }       





        
    </style>
@stop




