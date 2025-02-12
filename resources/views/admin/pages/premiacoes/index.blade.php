@extends('adminlte::page')
@section('title', 'Contrato')
@section('plugins.jqueryUi', true)
@section('plugins.Toastr', true)
@section('plugins.Datatables', true)
@section('content_header')
        
    <ul class="list_abas">
        <li data-id="aba_individual" class="ativo">Individual</li>
        <li data-id="aba_coletivo">Coletivo</li>
        <li data-id="aba_empresarial">Empresarial</li>
    </ul>

@stop

@section('content_top_nav_right')
    <li class="nav-item"><a href="" class="nav-link div_info"><i class="fas fa-cogs text-white"></i></a></li>
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt text-white"></i>
    </a>
@stop





@section('content')

    <div class="container_div_info">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.  
    </div>



   
    <section class="conteudo_abas">
        <!--------------------------------------INDIVIDUAL------------------------------------------>
        <main id="aba_individual">
           
            <section class="d-flex justify-content-between" style="flex-wrap: wrap;align-content: flex-start;">
            
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">                    

                    

                    <div class="mb-1">
                        <select id="select_usuario_individual" class="form-control">
                            <option value="todos">--Vendedores--</option>
                            
                        </select>
                    </div>

                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">
                        <h5 class="text-center d-flex align-items-center justify-content-center py-2 border-bottom">Pendentes</h5>


                        <ul style="margin:0;padding:0;list-style:none;" id="listar">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                                <span>Em Análise</span>
                               <span class="badge badge-info">1</span>                        
                            </li>



                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                                <span>Pag. Individual</span>
                               <span class="badge badge-info">1</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>2º Parcela</span>
                               <span class="badge badge-info">1</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>3º Parcela</span>
                               <span class="badge badge-info">1</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>4º Parcela</span>
                               <span class="badge badge-info">1</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>5º Parcela</span>
                               <span class="badge badge-info">1</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:3px;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>6º Parcela</span>
                               <span class="badge badge-info">1</span>                        
                            </li>
                        </ul>
                    </div>
                </div>
                <!--Fim Coluna da Esquerda  -->


                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:50%;">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                        <table id="tabela_individual" class="table listarindividual">
                            <thead>
                                <tr>
                                    <th>Corretor</th>
                                    <th>Cliente</th>
                                    <th>Data</th>
                                    <th>Valor Plano</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div>
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                <!---------DIREITA-------------->    
                <div class="mr-1 coluna-right">
                    <section class="p-1" style="background-color:#123449;">

                        <div class="d-flex">
                            <div style="flex-basis:63%;margin:0 1% 0 0;">
                                <span class="text-white" style="font-size:0.9em;">Cliente:</span>
                                <input type="text" name="cliente" id="cliente" class="form-control form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:36%;">
                                <span class="text-white" style="font-size:0.9em;">Data Nascimento:</span>
                                <input type="text" name="data_nascimento" id="data_nascimento" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div style="flex-basis:50%;">
                                <span class="text-white" style="font-size:0.9em;">Cidade:</span> 
                                <input type="text" name="cidade" id="cidade" class="form-control  form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:10%;margin:0 1%;">
                                <span class="text-white">UF:</span>
                                <input type="text" name="uf" id="uf" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:38%;" id="status">
                                <span class="text-white">Status:</span>
                                <select name="estagio_contrato" id="estagio_contrato" class="form-control form-control-sm" readonly>
                                    <option value="">-Status do Contrato-</option>
                                    <option value="1">Pag. Adesão</option>
                                    <option value="2">Pag. Vigência</option>
                                    <option value="3">Pag. Comissão</option>
                                    <option value="4">Pag. Premiação</option>
                                    <option value="5">Finalizado</option>
                                </select>
                            </div>    
                           
                        </div>

                        <div class="d-flex mb-1">
                            <div style="flex-basis:29%;margin-right:1%;">
                                <span class="text-white" style="font-size:0.9em;">Telefone:</span>
                                <input type="text" name="telefone" id="telefone" class="form-control form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:70%;">
                                <span class="text-white" style="font-size:0.9em;">Email:</span>
                                <input type="text" name="email" id="email" class="form-control form-control-sm" readonly>
                            </div>
                        </div>    

                        <div class="d-flex mb-2">
                            <div style="flex-basis:28%;">
                                <span class="text-white" style="font-size:0.9em;">CPF:</span>
                                <input type="text" name="cpf" id="cpf" class="form-control form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:38%;margin:0 1%;">
                                <span class="text-white" style="font-size:0.9em;">Responsavel Financeiro:</span>
                                <input type="text" name="responsavel_financeiro" id="responsavel_financeiro" class="form-control  form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:32%;">
                                <span class="text-white" style="font-size:0.9em;">CPF Financeiro:</span>
                                <input type="text" name="cpf_financeiro" id="cpf_financeiro" class="form-control  form-control-sm" readonly>
                            </div>    
                        </div>

                        <div class="d-flex mb-2">
                            <div style="flex-basis:19%;">
                                <span class="text-white" style="font-size:0.9em;">CEP:</span>
                                <input type="text" name="cep" id="cep_individual_cadastro" class="form-control form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:40%;margin:0 2%;">
                                <span class="text-white" style="font-size:0.9em;">Bairro:</span>
                                <input type="text" name="bairro" id="bairro_individual_cadastro" class="form-control form-control-sm" readonly>
                            </div>    
                            <div style="flex-basis:40%;">
                                <span class="text-white" style="font-size:0.9em;">Rua:</span>
                                <input type="text" name="rua" id="rua_individual_cadastro" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                        <div class="d-flex mb-2">
                            <div style="flex-basis:30%;">
                                <span class="text-white" style="font-size:0.9em;">Administradora:</span>
                                <input type="text" name="administradora_individual" id="administradora_individual" class="form-control  form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:35%;margin:0 2%;">
                                <span class="text-white" style="font-size:0.9em;">Codigo Externo:</span>
                                <input type="text" name="codigo_externo" id="codigo_externo_individual" class="form-control  form-control-sm" readonly>
                            </div>    

                            <div style="flex-basis:32%">    
                                <span class="text-white" style="font-size:0.9em;">Tipo Plano</span>
                                <input type="text" name="tipo_plano" id="tipo_plano_individual" class="form-control  form-control-sm" readonly>
                            </div>
                        </div>    
                
                        <div class="d-flex mb-2">
                            <div style="flex-basis:32%;">
                                <span class="text-white" style="font-size:0.9em;">Data Contrato:</span>
                                <input type="text" name="data_contrato" id="data_contrato" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:32%;margin:0 2%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Contrato:</span>
                                <input type="text" name="valor_contrato" id="valor_contrato" class="form-control  form-control-sm" readonly>
                            </div>

                             <div style="flex-basis:32%;">
                                <span class="text-white" style="font-size:0.9em;">Data Vigência:</span>
                                <input type="text" name="data_vigencia" id="data_vigencia" class="form-control  form-control-sm" readonly>
                            </div>
                        </div>

                        <div class="d-flex">

                            <div style="flex-basis:20%;">
                                <span class="text-white" style="font-size:0.9em;">Data Boleto:</span>
                                <input type="text" name="data_boleto" id="data_boleto" class="form-control  form-control-sm" readonly>
                            </div>

                             <div style="flex-basis:20%;margin:0 1%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Adesão:</span>
                                <input type="text" name="valor_adesao" id="valor_adesao" class="form-control  form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:18%;margin-right:1%;">
                                <div class="form-group d-flex justify-content-center flex-column" id="coparticipacao">
                                    <span class="text-white" style="font-size:0.9em;">Coparticipação:</span>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-light" id="coparticipacao_sim" style="padding:0.20rem 0.70rem;">
                                            <input type="radio" name="coparticipacao" id="coparticipacao_radio_sim" disabled  value="sim"> Sim
                                        </label>
                                        <label class="btn btn-outline-light" id="coparticipacao_nao" style="padding:0.20rem 0.70rem;">
                                            <input type="radio" name="coparticipacao" id="coparticipacao_radio_nao" disabled value="nao"> Não
                                        </label>
                                        
                                    </div>
                                    
                                </div>
                            </div>    
                            
                            <div style="flex-basis:18%;margin-right:1%;">
                                <div class="form-group  d-flex justify-content-center flex-column" id="odonto">
                                    <span class="text-white" style="font-size:0.9em;">Odonto:</span>                            
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-light" id="odonto_sim" style="padding:0.21rem 0.70rem;">
                                            <input type="radio" name="odonto" id="odonto_radio_sim" disabled value="sim"> Sim
                                        </label>
                                        <label class="btn btn-outline-light" id="odonto_nao" style="padding:0.21rem 0.70rem;">
                                            <input type="radio" name="odonto" id="odonto_radio_nao" disabled value="nao"> Não
                                        </label>                      
                                    </div>                           
                                </div>
                            </div> 

                            <div style="flex-basis:8%">    
                                <span class="text-white" style="font-size:0.9em;">Vidas</span>
                                <input type="text" name="quantidade_vidas" id="quantidade_vidas_individual_cadastrar" class="form-control  form-control-sm" readonly>
                            </div>
                        </div>                
                    </section>

                    
                     
                </div>    









                <!---------FIM DIREITA-------------->    
            </section>

       </main><!-------------------------------------DIV FIM Individial------------------------------------->     
       <!-------------------------------------FIM Individial------------------------------------->



       <!------------------------------------------COLETIVO---------------------------------------------------->
       <main id="aba_coletivo" class="ocultar">

           

             <section class="d-flex justify-content-between" style="flex-wrap: wrap;">
            

                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">
                    <div class="d-flex flex-column">

                       

                        <select class="my-2 form-control" style="flex-basis:80%;" id="select_coletivo">
                            <option value="todos">--Administradora--</option>
                                       
                        </select>

                        <select class="my-2 form-control" style="flex-basis:80%;" id="select_usuario">
                            <option value="todos">--Vendedores--</option>
                        </select>



                    </div>
                        
                    
                    
                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">
                        <h5 class="text-center d-flex align-items-center justify-content-center py-2 border-bottom">Pendentes</h5>
                        <ul style="margin:0;padding:0;list-style:none;" id="listar">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_boleto_coletivo" class="fundo">
                                <span>Em Analise</span>
                                <span class="badge badge-info">10</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_adesao_coletivo">
                                <span>Emissão Boleto</span>
                                <span class="badge badge-info">20</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento Adesão</span>
                                <span class="badge badge-info">30</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento Vigência</span>
                                <span class="badge badge-info">30</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento 2º Parcela</span>
                                <span class="badge badge-info">30</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento 3º Parcela</span>
                                <span class="badge badge-info">30</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento 4º Parcela</span>
                                <span class="badge badge-info">30</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento 5º Parcela</span>
                                <span class="badge badge-info">30</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:2px;" id="aguardando_pagamento_vigencia">
                                <span>Pagamento 6º Parcela</span>
                                <span class="badge badge-info">30</span>
                            </li>
                        </ul>
                    </div>
                </div>    
                <!--FIM COLUNA DA ESQUERDA-->


                <!--COLUNA DA CENTRAL-->
                <div class="p-1" style="flex-basis:50%;background-color:#123449;color:#FFF;">
                    <div class="p-2">
                        <table id="tabela_coletivo" class="table listardados">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Administradora</th>
                                    <th>Cliente</th>
                                    <th>Corretor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div> 
                </div>  
                <!--FIM COLUNA DA CENTRAL-->


                <!--COLUNA DA DIREITA-->    
                <div class="mr-1 coluna-right">
                    <section class="p-1" style="background-color:#123449;">
                        
                        <div class="d-flex">
                            <div style="flex-basis:63%;margin:0 1% 0 0;">
                                <span class="text-white" style="font-size:0.9em;">Cliente:</span>
                                <input type="text" name="cliente_coletivo" id="cliente_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:36%;">
                                <span class="text-white" style="font-size:0.9em;">Data Nascimento:</span>
                                <input type="text" name="data_nascimento_coletivo" id="data_nascimento_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                        <div class="d-flex">

                            <div style="flex-basis:50%;">
                                <span class="text-white" style="font-size:0.9em;">Cidade:</span> 
                                <input type="text" name="cidade_coletivo" id="cidade_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:10%;margin:0 1%;">
                                <span class="text-white">UF:</span>
                                <input type="text" name="uf_coletivo" id="uf_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:38%;" id="status">
                                <span class="text-white">Status:</span>
                                
                                <input type="text" id="estagio_contrato_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>    
                   
                        </div>

                        <div class="d-flex mb-1">

                            <div style="flex-basis:29%;margin-right:1%;">
                                <span class="text-white" style="font-size:0.9em;">Telefone:</span>
                                <input type="text" name="telefone_coletivo" id="telefone_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:70%;">
                                <span class="text-white" style="font-size:0.9em;">Email:</span>
                                <input type="text" name="email_coletivo" id="email_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                        </div>

                        <div class="d-flex mb-2">

                            <div style="flex-basis:28%;">
                                <span class="text-white" style="font-size:0.9em;">CPF:</span>
                                <input type="text" name="cpf_coletivo" id="cpf_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:38%;margin:0 1%;">
                                <span class="text-white" style="font-size:0.9em;">Responsavel Financeiro:</span>
                                <input type="text" name="responsavel_financeiro_coletivo_view" id="responsavel_financeiro_coletivo" class="form-control  form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:32%;">
                                <span class="text-white" style="font-size:0.9em;">CPF Financeiro:</span>
                                <input type="text" name="cpf_financeiro_coletivo" id="cpf_financeiro_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>    

                        </div>

                        <div class="d-flex mb-2">

                            <div style="flex-basis:19%;">
                                <span class="text-white" style="font-size:0.9em;">CEP:</span>
                                <input type="text" name="cep_coletivo" id="cep_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:40%;margin:0 2%;">
                                <span class="text-white" style="font-size:0.9em;">Bairro:</span>
                                <input type="text" name="bairro_coletivo" id="bairro_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>    
                    
                            <div style="flex-basis:40%;">
                                <span class="text-white" style="font-size:0.9em;">Rua:</span>
                                <input type="text" name="rua_coletivo" id="rua_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                        </div>


                        <div class="d-flex mb-2">

                            <div style="flex-basis:30%;">
                                <span class="text-white" style="font-size:0.9em;">Administradora:</span>
                                <input type="text" name="administradora_coletivo" id="administradora_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:35%;margin:0 2%;">
                                <span class="text-white" style="font-size:0.9em;">Codigo Externo:</span>
                                <input type="text" name="codigo_externo_coletivo" id="codigo_externo_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>    

                            <div style="flex-basis:32%">    
                                <span class="text-white" style="font-size:0.9em;">Tipo Plano</span>
                                <input type="text" name="tipo_plano_coletivo" id="tipo_plano_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>

                        </div>    


                        <div class="d-flex mb-2">

                            <div style="flex-basis:32%;">
                                <span class="text-white" style="font-size:0.9em;">Data Contrato:</span>
                                <input type="text" name="data_contrato_coletivo" id="data_contrato_coletivo_view" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:32%;margin:0 2%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Contrato:</span>
                                <input type="text" name="valor_contrato_coletivo" id="valor_contrato_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>

                             <div style="flex-basis:32%;">
                                <span class="text-white" style="font-size:0.9em;">Data Vigência:</span>
                                <input type="text" name="data_vigencia_coletivo" id="data_vigencia_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>
                    
                        </div>


                         <div class="d-flex">

                            <div style="flex-basis:20%;">
                                <span class="text-white" style="font-size:0.9em;">Data Boleto:</span>
                                <input type="text" id="data_boleto_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>

                             <div style="flex-basis:20%;margin:0 1%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Adesão:</span>
                                <input type="text" id="valor_adesao_coletivo_view" class="form-control  form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:18%;margin-right:1%;">
                                <div class="form-group d-flex justify-content-center flex-column" id="coparticipacao">
                                    <span class="text-white" style="font-size:0.9em;">Coparticipação:</span>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-light" id="coparticipacao_sim" style="padding:0.20rem 0.70rem;">
                                            <input type="radio" id="coparticipacao_radio_sim_coletivo_view" disabled  value="sim"> Sim
                                        </label>
                                        <label class="btn btn-outline-light" id="coparticipacao_nao" style="padding:0.20rem 0.70rem;">
                                            <input type="radio" id="coparticipacao_radio_nao_coletivo_view" disabled value="nao"> Não
                                        </label>
                                        
                                    </div>
                                    
                                </div>
                            </div>    
                            
                            <div style="flex-basis:18%;margin-right:1%;">
                                <div class="form-group  d-flex justify-content-center flex-column" id="odonto">
                                    <span class="text-white" style="font-size:0.9em;">Odonto:</span>                            
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-light" id="odonto_sim" style="padding:0.21rem 0.70rem;">
                                            <input type="radio" id="odonto_radio_sim" disabled value="sim"> Sim
                                        </label>
                                        <label class="btn btn-outline-light" id="odonto_nao" style="padding:0.21rem 0.70rem;">
                                            <input type="radio" id="odonto_radio_nao" disabled value="nao"> Não
                                        </label>                      
                                    </div>                           
                                </div>
                            </div> 

                            <div style="flex-basis:8%">    
                                <span class="text-white" style="font-size:0.9em;">Vidas</span>
                                <input type="text" name="quantidade_vidas" id="quantidade_vidas_individual_cadastrar" class="form-control  form-control-sm" readonly>
                            </div>
                        </div>                
                    </section>    
                </div>
            </section>
       </main>

       <main id="aba_empresarial" class="ocultar">
            <section class="d-flex justify-content-between" style="flex-wrap: wrap;">
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">                    
                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">
                        <h5 class="text-center d-flex align-items-center justify-content-center py-2 border-bottom">Pendentes</h5>
                        <select name="mudar_user_empresarial" class="form-control">
                            <option>----Escolher o Corretor----</option>    
                        </select>
                        <ul style="margin:0;padding:0;list-style:none;" id="listar">                            
                            <li style="padding:9px 5px;display:flex;justify-content:space-between;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>Pag. Individual</span>
                               <span class="badge badge-info">10</span>                        
                            </li>

                            <li style="padding:9px 5px;display:flex;justify-content:space-between;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>2º Parcela</span>
                               <span class="badge badge-info">10</span>                        
                            </li>

                            <li style="padding:9px 5px;display:flex;justify-content:space-between;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>3º Parcela</span>
                               <span class="badge badge-info">10</span>                        
                            </li>

                            <li style="padding:9px 5px;display:flex;justify-content:space-between;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>4º Parcela</span>
                               <span class="badge badge-info">10</span>                        
                            </li>

                            <li style="padding:9px 5px;display:flex;justify-content:space-between;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>5º Parcela</span>
                               <span class="badge badge-info">10</span>                        
                            </li>

                            <li style="padding:9px 5px;display:flex;justify-content:space-between;" id="aguardando_boleto_coletivo" class="fundo">
                               <span>6º Parcela</span>
                               <span class="badge badge-info">10</span>                        
                            </li>


                        </ul>
                    </div>
                </div>
                <!--Fim Coluna da Esquerda  -->

                <!--COLUNA DA CENTRAL-->
                <div class="p-1" style="flex-basis:50%;background-color:#123449;color:#FFF;">
                    <div class="p-2">
                        <table id="tabela_empresarial" class="table listarempresarial">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Cod. Cliente</th>
                                    <th>QTD Vidas</th>
                                    <th>CNPJ</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div> 
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                <!---Coluna Direita--->
                <div class="mr-1 coluna-right">
                    <section class="p-1" style="background-color:#123449;">

                        <div class="d-flex">
                            <div style="flex-basis:63%;margin:0 1% 0 0;">
                                <span class="text-white" style="font-size:0.9em;">Codigo Vendedor:</span>
                                <input type="text" id="codigo_vendedor_empresarial_view" class="form-control form-control-sm" readonly>
                            </div>
                            <div style="flex-basis:36%;">
                                <span class="text-white" style="font-size:0.9em;">Codigo Cliente:</span>
                                <input type="text" id="codigo_cliente_empresarial_view" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div style="flex-basis:40%;">
                                <span class="text-white" style="font-size:0.9em;">Plano:</span> 
                                <input type="text" name="plano_view_empresarial" id="plano_view_empresarial" class="form-control  form-control-sm" readonly>
                            </div>
                            
                            <div style="flex-basis:60%;margin:0 1%;">
                                <span class="text-white">Razão Social:</span>
                                <input type="text" id="razao_social_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>
                                
                   
                        </div>

                        <div class="d-flex mb-1">

                            <div style="flex-basis:70%;">
                                <span class="text-white">CNPJ:</span>
                                <input type="text" name="cnpj_view" id="cnpj_view" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:30%;margin:0 0 0 1%;">
                                <span class="text-white">QTD Vidas:</span>
                                <input type="text" id="qtd_vidas" class="form-control form-control-sm" readonly>
                            </div>


                        </div>


                        <div class="d-flex mb-1">
                            
                            <div style="flex-basis:24%;margin-right:1%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Boleto:</span>
                                <input type="text" id="valor_boleto_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:24%;margin-right:1%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Plano:</span>
                                <input type="text" id="valor_plano_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:24%;margin-right:1%;">
                                <span class="text-white" style="font-size:0.9em;">Valor Total:</span>
                                <input type="text" id="valor_total_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:24%;">
                                <span class="text-white" style="font-size:0.9em;">Taxa Adesão:</span>
                                <input type="text" id="taxa_adesao_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                        </div>


                        <div class="d-flex mb-1">


                            <div style="flex-basis:39%;">
                                <span class="text-white" style="font-size:0.9em;">Vendedor:</span>
                                <input type="text" id="vendedor_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:30%;margin:0 1%;">
                                <span class="text-white" style="font-size:0.9em;">Vencimento Boleto:</span>
                                <input type="text" id="vencimento_boleto_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                            <div style="flex-basis:30%;">
                                <span class="text-white" style="font-size:0.9em;">Dia Vencimento:</span>
                                <input type="text" id="quantidade_vidas_view_empresarial" class="form-control form-control-sm" readonly>
                            </div>

                        </div>    

                        
                </div>
                <!---------FIM DIREITA----------->
           </section>    

       </main>
    </section>


  


   

   
@stop  

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>   
   
    <script>
        $(function(){
            
            

        	var taindividual = $(".listarindividual").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_individual">ft><t><"d-flex justify-content-between"lp>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('premiacao.listar') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"user.name",name:"corretor"},
                    {data:"contrato.clientes.nome",name:"cliente"},
                    {data:"data",name:"data"},
                    {data:"contrato.valor_plano",name:"valor"},
                    
                ],
                "columnDefs": [
                    {
                        /** Data*/
                        "targets": 0,
                        "width":"35%"
                    },
                   
                    {
                        "targets": 1,
                        "width":"35%"
                    },
                    {
                        "targets": 2,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        },
                        "width":"10%"
                    },
                    {
                        "targets": 3,
                        "width":"15%"
                    },
                    
               ],
                
                "initComplete": function( settings, json ) {
                    $('#title_individual').html("<h4>Individual</h4>");

                     this.api()
                       .columns([0])
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

                            column.data().unique().sort().each(function (d, j) {
                                selectUsuarioIndividual.append('<option value="' + d + '">' + d + '</option>');
                            });
                       })



                }
            });





        });
    </script>
@stop


@section('css')
    <style>
        .ativo {background-color:#FFF !important;color: #000 !important;}
        .ocultar {display: none;}
        .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
        .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-top:2px solid black;border-right:2px solid black;border-left:2px solid black;border-radius: 5px 5px 0 0;background-color: rgba(0,0,0,0.5);}
        .list_abas li:hover {cursor: pointer;}    
        .list_abas li:nth-of-type(2) {margin: 0 1%;}
        .textoforte {background-color:rgba(255,255,255,0.5);color:black;}
        .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;}
        .valores-acomodacao {background-color:#123449;color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;}
        .valores-acomodacao:hover {cursor:pointer;box-shadow: none;}
        .table thead tr {background-color:#123449;color: white;}
        .destaque {border:4px solid rgba(36,125,157);}
        #coluna_direita {flex-basis:10%;background-color:#123449;border-radius: 5px;}
        #coluna_direita ul {list-style: none;margin: 0;padding: 0;}
        #coluna_direita li {color:#FFF;}
        .coluna-right {flex-basis:33%;flex-wrap: wrap;border-radius:5px;height:720px;}
        .container_div_info {
            background-color:orange;position:absolute;width:500px;right:0px;top:57px;min-height: 700px;
            display: none;z-index: 1;
        }
    </style>
@stop




 