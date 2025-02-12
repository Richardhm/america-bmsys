@extends('adminlte::page')
@section('title', 'Cadastrar Coletivo')
@section('plugins.jqueryUi', true)
@section('plugins.Toastr', true)
@section('content_header')
	<h2 class="text-white">Coletivo Adesão</h2>
@stop

@section('content_top_nav_right')
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    <!-- <li class="nav-item"><a href="" class="nav-link div_info"><i class="fas fa-cogs text-white"></i></a></li> -->
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content')
	
    <div class="modal fade" id="mudarDataCriacao" tabindex="-1" role="dialog" aria-labelledby="mudarDataCriacaoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mudarDataCriacaoLabel">Data de Cadastro:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="date" value="<?php echo date('Y-m-d');?>" class="form-control" id="data_criacao">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Salvar Data</button>            
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDiferencaEntreValores" tabindex="-1" role="dialog" aria-labelledby="modalDiferencaEntreValoresLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDiferencaEntreValoresLabel">Data de Cadastro:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="text-center">
                <p>Diferença entre valores: <span class="diferenca_entre_valores"></span>   </p>
            </div>

            <div style="display:flex;justify-content: space-around;">
                <div style="display:flex;flex-direction: column;">   
                    <span>Corretora:</span>        
                    <input type="text" id="desconto_corretora_valores">
                </div>   
                <div style="display:flex;flex-direction: column;">
                    <span>Corretor</span> 
                    <input type="text" id="desconto_corretor_valores" disabled>
                </div>
            </div>


            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Salvar Valores</button>
                
            </div>
            </div>
        </div>
    </div>

























	<div style="background-color:#123449;border-radius:5px;padding:10px 5px;">
	
	<form action="" method="post" class="px-3" name="cadastrar_pessoa_fisica_formulario_modal_coletivo" id="cadastrar_pessoa_fisica_formulario_modal_coletivo">
             
            @csrf              

            <input type="hidden" name="tipo_cadastro" value="administrador_cadastro">

            <input type="hidden" name="created_at" id="created_at">

            <input type="hidden" name="desconto_corretor" id="desconto_corretor">
            <input type="hidden" name="desconto_corretora" id="desconto_corretora">




            <input type="hidden" name="tipo_cadastro" value="corretor_cadastro">

            <input type="hidden" name="usuario_coletivo_switch" id="usuario_coletivo_switch" value="{{Auth::user()->id}}">

            <!-- Primeiro Linha -->
            <div class="d-flex">
                
                <div style="flex-basis:20%;margin-right:1%;">
                    <div class="form-group">
                        <span for="administradora" class="text-white">Administradora:</span>
                        <select required id="administradora_coletivo" class="form-control  form-control-sm">
                            <option value="">-- Administradora--</option>
                            @foreach($administradoras as $admin)
                                <option value="{{$admin->id}}" {{old('administradora') == $admin->id ? 'selected' : ''}}>{{$admin->nome}}</option>
                            @endforeach
                            <option value=""></option>
                            
                        </select>    
                        <div class="erroradministradora"></div>
                    </div>
                </div>

                <div style="flex-basis:13%;margin-right: 1%;">
                    <div class="form-group">
                        <span for="tabela_origem" class="text-white">Tabela Origem:</span>
                        <select required id="tabela_origem_coletivo" name="tabela_origem" class="form-control form-control-sm change_valores">
                            <option value="">--Tabela Origem--</option>
                            @foreach($cidades as $cc)
                                <option value="{{$cc->id}}" {{old('cidade_id') == $cc->id ? 'selected' : ''}}>{{$cc->nome}}</option>
                            @endforeach
                        </select>   
                       <div class="errorcidade"></div>
                    </div>
                </div>

                <div style="flex-basis:17%">
                    <div class="form-group">
                        <span for="nome" class="text-white">Titular:</span>
                        <input type="text" id="nome_coletivo" name="nome_coletivo" required class="form-control form-control-sm" placeholder="Nome" value="">
                        <div class="errorcliente"></div>
                    </div>
                </div>

                <div style="flex-basis:11%;margin:0% 1%;">
                    <div class="form-group">
                        <span for="cpf" class="text-white">CPF:</span>
                        <input type="text" name="cpf_coletivo" id="cpf_coletivo" required class="form-control form-control-sm" value="{{old('cpf')}}" placeholder="XXX.XXXX.XXX-XX">
                        <div class="errorcpf"></div>
                        @if($errors->has('cpf'))
                            <p class="alert alert-danger">{{$errors->first('cpf')}}</p>
                        @endif
                    </div>
                </div>

                <div style="flex-basis:9%;margin-right:1%;">
                    <div class="form-group">
                        <span for="data_nascimento" class="text-white">Data Nascimento:</span>
                        <input type="date" name="data_nascimento_coletivo" value="{{old('data_nascimento_coletivo')}}" required id="data_nascimento_coletivo" class="form-control  form-control-sm">
                        <div class="errordatanascimento"></div>
                    </div>
                </div>

                <div style="flex-basis:15%;margin-right:1%;">
                    <div class="form-group">
                        <span for="email" class="text-white">Email:</span>
                        <input type="email" name="email_coletivo" id="email_coletivo" required placeholder="Email" class="form-control  form-control-sm" value="">
                        <div class="erroremail"></div>
                    </div>
                </div>    

                <div style="flex-basis:5;margin-right: 1%;">
                    <span for="celular" class="text-white">Celular:</span>
                    <input type="text" placeholder="Celular" class="form-control form-control-sm" name="celular" id="celular" />
                </div>

                <div style="flex-basis:5;">
                    <span for="telefone" class="text-white">Telefone:</span>
                    <input type="text" placeholder="Telefone" class="form-control form-control-sm" name="telefone" id="telefone" />
                </div>    

            </div>    
            <!-- Fim Primeiro Linha -->

            <!-- Segunda Linha -->                
            <div class="d-flex">                 

                <div style="flex-basis:8%;margin-right:1%;">
                    <div class="form-group">
                        <span for="cep" class="text-white">CEP:</span>
                        <input type="text" name="cep_coletivo" required id="cep_coletivo" value="{{old('cep_coletivo')}}" placeholder="CEP" class="form-control  form-control-sm" value="">
                        <div class="errorcep"></div>
                    </div>
                </div>

                <div class="form-group" style="flex-basis:10%;margin-right:1%;">
                    <span for="rua" class="text-white">Cidade:</span>
                    <input type="text" name="cidade_origem_coletivo" required id="cidade_origem_coletivo" value="{{old('cidade_origem_coletivo')}}" placeholder="Cidade" class="form-control  form-control-sm" value="">
                    <div class="errorlogradouro"></div>
                </div>

                <div class="form-group" style="flex-basis:13%;margin-right:1%;">
                    <span for="bairro" class="text-white">Bairro:</span>
                    <input type="text" name="bairro_coletivo" required id="bairro_coletivo" value="{{old('bairro_coletivo')}}" placeholder="Bairro" class="form-control  form-control-sm" value="">
                    
                </div>

                <div class="form-group" style="flex-basis:12%;margin-right:1%;">
                    <span for="rua" class="text-white">Rua:</span>
                    <input type="text" name="rua_coletivo" required id="rua_coletivo" value="{{old('rua_coletivo')}}" placeholder="Logradouro(Rua)" class="form-control  form-control-sm" value="">
                    
                </div>

                <div class="form-group" style="flex-basis:15%;">
                    <span for="bairro" class="text-white">Complemento:</span>
                    <input type="text" name="complemento_coletivo" id="complemento_coletivo" value="{{old('complemento_coletivo')}}" placeholder="Complemento(Opcional)" class="form-control  form-control-sm" value="">
                    
                </div>


                <div class="form-group" style="flex-basis:5%;margin-left:1%;">
                    <span for="uf" class="text-white">UF:</span>
                    <input type="text" name="uf_coletivo" required id="uf_coletivo" value="{{old('uf_coletivo')}}" placeholder="UF" class="form-control  form-control-sm" value="">
                    
                </div>


                

                <div style="flex-basis:10%;margin:0 1%;">
                    <div class="form-group">
                        <span for="codigo_externo" class="text-white">Codigo Externo:</span>
                        <input type="text" name="codigo_externo_coletivo" required id="codigo_externo_coletivo" value="{{old('codigo_externo_coletivo')}}" class="form-control  form-control-sm" placeholder="COD.">
                        
                    </div>
                </div>  

                <div style="flex-basis:10%;margin:0 1% 0 0">
                    <div class="form-group d-flex justify-content-center flex-column">
                        <span class="text-white">Coparticipação:</span>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-light" id="coparticipacao_sim" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="coparticipacao_coletivo" id="coparticipacao_radio_sim"  value="sim" {{old('coparticipacao_coletivo') == "sim" ? 'checked' : ''}}> Sim
                            </label>
                            <label class="btn btn-outline-light" id="coparticipacao_nao" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="coparticipacao_coletivo" id="coparticipacao_radio_nao" value="nao" {{old('coparticipacao_coletivo') == "nao" ? 'checked' : ''}}> Não
                            </label>
                            
                        </div>
                        
                    </div>
                </div>        

                <div style="flex-basis:10%;">
                    <div class="form-group  d-flex justify-content-center flex-column">
                        <span for="odonto" class="text-white">Odonto:</span>
                        
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-light" id="odonto_sim" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="odonto_coletivo" id="odonto_radio_sim" value="sim" {{old('odonto') == "sim" ? 'checked' : ''}}> Sim
                            </label>
                            <label class="btn btn-outline-light" id="odonto_nao" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="odonto_coletivo" id="odonto_radio_nao" value="nao" {{old('odonto') == "nao" ? 'checked' : ''}}> Não
                            </label>
                            
                        </div>
                        <div class='errorodonto'></div>
                    </div>
                </div>   


            </div>
            <!-- Fim Segunda Linha -->
        
            <div class="d-flex">
                    
                <div style="flex-basis:10%;">
                    <input type="checkbox" id="dependente_coletivo" name="dependente_coletivo"><span class="text-white">Responsável</span>
                </div>

                <div style="flex-basis:90%;" class="d-none" id="container_responsavel_coletivo">

                    <div class="d-flex">                      
                        <div style="flex-basis:30%;margin-right:1%;display:flex;">
                           
                                <span style="flex-basis:30%;" for="codigo_externo" class="text-white">Responsável:</span>
                                <input style="flex-basis:70%;" type="text" name="responsavel_financeiro_coletivo_cadastrar_nome" id="responsavel_financeiro_coletivo_cadastrar_nome" value="{{old('responsavel_financeiro_coletivo_cadastrar_nome')}}" class="form-control  form-control-sm" placeholder="Nome Responsavel">
                        </div>  
                        <div style="flex-basis:70%;display:flex;">                            
                                <span style="flex-basis:17%;" for="codigo_externo" class="text-white">CPF Responsável:</span>
                                <input style="flex-basis:30%;" type="text" name="responsavel_financeiro_coletivo_cadastrar_cpf" id="responsavel_financeiro_coletivo_cadastrar_cpf" value="{{old('responsavel_financeiro_coletivo_cadastrar_cpf')}}" class="form-control  form-control-sm" placeholder="CPF Responsavel">
                       </div>
                    </div>

                </div>
            </div>
            <!--Faixas Etarias--->
           <section>
                <div class="errorfaixas"></div>                   
                    <div class="d-flex">
                        
                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">0-18:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-0-18_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_0_18" name="faixas_etarias[1]" value="{{isset($colunas) && in_array(1,$colunas) ? $faixas[array_search(1, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-0-18_coletivo" class="text-center font-weight-bold flex-fill faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="d-flex align-items-center justify-content-center plus" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      


                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">19-23:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-19-23_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_19_23" name="faixas_etarias[2]" value="{{isset($colunas) && in_array(2,$colunas) ? $faixas[array_search(2, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-19-23_coletivo" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="d-flex align-items-center justify-content-center plus" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">24-28:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-24-28_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_24_28" name="faixas_etarias[3]" value="{{isset($colunas) && in_array(3,$colunas) ? $faixas[array_search(3, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-24-28_coletivo" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">29-33:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-29-33_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_29_33" name="faixas_etarias[4]" value="{{isset($colunas) && in_array(4,$colunas) ? $faixas[array_search(4, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-29-33_coletivo" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">34-38:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-34-38_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[5]" data-change="change_faixa_34_38" value="{{isset($colunas) && in_array(5,$colunas) ? $faixas[array_search(5, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-34-38_coletivo" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>              

                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">39-43:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-39-43_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[6]" data-change="change_faixa_39_43" value="{{isset($colunas) && in_array(6,$colunas) ? $faixas[array_search(6, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-39-43_coletivo" class="text-center font-weight-bold flex-fill w-25 faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">44-48:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-44-48_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[7]" data-change="change_faixa_44_48" value="{{isset($colunas) && in_array(7,$colunas) ? $faixas[array_search(7, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-44-48_coletivo" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">49-53:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-49-53_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[8]" data-change="change_faixa_49_53" value="{{isset($colunas) && in_array(8,$colunas) ? $faixas[array_search(8, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-49-53_coletivo" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;margin:0 10px 0 0;">
                            <span for="" class="text-white">54-58:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-54-58_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[9]" data-change="change_faixa_54_58" value="{{isset($colunas) && in_array(9,$colunas) ? $faixas[array_search(9, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-54-58_coletivo"  class="text-center font-weight-bold faixas_etarias d-flex" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">59+</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">

                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger"  id="faixa-59_individual" style="border:none;background:#FF0000;width:30%;max-height: 30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    
                                    <input type="tel" data-change="change_faixa_59" name="faixas_etarias[10]" value="{{isset($colunas) && in_array(10,$colunas) ? $faixas[array_search(10, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-59_coletivo" class="text-center font-weight-bold faixas_etarias d-flex" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" />
                                    
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height: 30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>

                    </div>
                    <!--Fim Faixa Etaria-->                      
                </section> 
                <div class="form-row mt-3">
                    <div class="col-12 d-flex rounded">
                        <button id="mostrar_plano_coletivo" class="w-100">Mostrar Planos</button>
                    </div>
                </div>
                <div id="resultado_coletivo">
                </div>    
            </form>

    </div>        



@stop


@section('css')
    <style>
        /* .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;} */
        /* .valores-acomodacao {background-color:rgba(0,0,0,0.5);color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;} */
        /* .valores-acomodacao:hover {cursor:pointer;box-shadow: none;} */
        /* .table thead tr {background-color:rgb(36,125,157);} */
        /* .table tbody tr:nth-child(odd) {background-color: rgba(0,0,0,0.5);} */
        /* .table tbody tr:nth-child(even) {background-color:rgb(36,125,157);} */
        .destaque {border:5px solid rgba(36,125,157) !important;box-shadow: 5px -9px 3px #000 !important; }
    </style>
@stop















@section('js')
	<script src="{{asset('js/jquery.mask.min.js')}}"></script>  
	<script>
		$(function(){


            function TestaCPF(strCPF) {
                var Soma;
                var Resto;
                Soma = 0;
                
                if (strCPF == "00000000000") return false;
                for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
                Resto = (Soma * 10) % 11;
                if ((Resto == 10) || (Resto == 11))  Resto = 0;
                if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
                Soma = 0;
                for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
                Resto = (Soma * 10) % 11;
                if ((Resto == 10) || (Resto == 11))  Resto = 0;
                if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
                return true;
            }

 
            $("#email_coletivo").on('keyup',(e) => {
                $('#email_coletivo').val($('#email_coletivo').val().toLowerCase());
            });

			function adicionaZero(numero){
                if (numero <= 9) 
                    return "0" + numero;
                else
                    return numero; 
            }

            $("#cep_coletivo").change(function(){
                let cep = $(this).val().replace("-","");
                const url = `https://viacep.com.br/ws/${cep}/json`;
                const options = {method: "GET",mode: "cors",
                    headers: {'content-type': 'application/json;charset=utf-8'}
                }
                fetch(url,options).then(response => response.json()).then(
                    data => {       
                        $("#rua_coletivo").val(data.logradouro);
                        $("#bairro_coletivo").val(data.bairro);
                        $("#complemento_coletivo").val(data.complemento);
                        $("#uf_coletivo").val(data.uf);
                        $("#cidade_origem_coletivo").val(data.localidade);
                    }
                )
                if($(this).val() != "") {
                    $(".errorcep").html('');
                }   
            });

            $('#cnpj').mask('00.000.000/0000-00');
            $('#telefone').mask('(00) 0000-0000');
            // $('#telefone_individual').mask('0000-0000');
            $('#celular_individual').mask('(00) 0 0000-0000');
            $('#celular').mask('(00) 0 0000-0000');
            $('#taxa_adesao').mask("#.##0,00", {reverse: true});
            $('#valor_plano').mask("#.##0,00", {reverse: true});
            $('#valor_total').mask("#.##0,00", {reverse: true});
            $('#valor_boleto').mask("#.##0,00", {reverse: true});
            $('#valor_plano_saude').mask("#.##0,00", {reverse: true});
            $('#desconto_corretora_valores').mask("#.##0,00", {reverse: true});



            $('#valor_plano_saude').mask("#.##0,00", {reverse: true});
            $('#valor_plano_odonto').mask("#.##0,00", {reverse: true});
            $('#cpf_individual').mask('000.000.000-00');
            $('#cpf_financeiro_individual_cadastro').mask('000.000.000-00');   
            $('#responsavel_financeiro_coletivo_cadastrar_cpf').mask('000.000.000-00');
            

            $('#cpf_coletivo').mask('000.000.000-00');  
            $('#cep_individual').mask('00000-000');          
            $('#cep_coletivo').mask('00000-000');      

			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    

			$("body").on('change','#dependente_coletivo',function(){
               if($(this).is(':checked')) {
                    $("#container_responsavel_coletivo").removeClass('d-none');
                } else {
                    $("#container_responsavel_coletivo").addClass('d-none');
                } 
            });

            let plus = $(".plus");
            let minus = $(".minus");
            $(plus).on('click',function(e){
                let alvo = e.target;
                let pai = alvo.closest('.content');
                let input = $(pai).find('input');
                if(input.val() == "") {
                    input.val(0);
                }
                let newValue = parseInt(input.val()) + 1;
                if(newValue >= 0) {
                    input.val(newValue);
                }
            });

            $(minus).on('click',function(e){
                let alvo = e.target;
                let pai = alvo.closest('.content');
                let input = $(pai).find('input');
                let newValue = parseInt(input.val()) - 1;
                
                if(newValue >= 0) {
                    input.val(newValue);
                }
            });

            $("body").on('change','input[name="boleto"]',function(){
                let data_boleto = $(this).val();                
                $(this).closest('form').find('#data_boleto').val(data_boleto);
            });

            $("body").on('change','input[name="adesao"]',function(){
                let valor_adesao = $(this).val();
                $(this).closest('form').find('#valor_adesao').val(valor_adesao);
            });

            $('#mudarDataCriacao').on('hidden.bs.modal', function (e) {
                let valor = $("#data_criacao").val();
                $("#created_at").val(valor);
            });

            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            
            $('#modalDiferencaEntreValores').on('hidden.bs.modal', function (e) {
                $('form[name="cadastrar_pessoa_fisica_formulario_modal_coletivo"]').submit();
            });

            
            $("#desconto_corretora_valores").change(function(){
                let valor = $(this).val().replace(".","").replace(",",".");
                let total = $(".diferenca_entre_valores").text().replace("R$","").replace(".","").replace(",",".").trim();
                let corretor = total - valor;
                let resto_corretor = parseFloat(corretor);
                $("#desconto_corretor_valores").val(resto_corretor.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                $("#desconto_corretor").val(resto_corretor);
                $("#desconto_corretora").val(valor);
            });







            $("body").find('form[name="cadastrar_pessoa_fisica_formulario_modal_coletivo"]').on("click","#mostrar_plano_coletivo",function(){

                if($("#usuario_coletivo_switch").val() == "") {
                    toastr["error"]("Vendedor é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#administradora_coletivo").val() == "") {
                    toastr["error"]("Administradora é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#tabela_origem_coletivo").val() == "") {
                    toastr["error"]("Tabela Origem campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }



                if($("#nome_coletivo").val() == "") {
                    toastr["error"]("Titular é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#cpf_coletivo").val() == "") {
                    toastr["error"]("CPF é obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if(!TestaCPF($("#cpf_coletivo").val().replace(/[^0-9]/g,''))) {
                    toastr["error"]("CPF Inválido")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }


                 




                 if($("#data_nascimento_coletivo").val() == "") {
                    toastr["error"]("Data Nascimento campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#email_coletivo").val() == "") {
                    toastr["error"]("Email campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#celular").val() == "") {
                    toastr["error"]("Celular é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }
                
                 
                 if($("#cep_coletivo").val() == "") {
                    toastr["error"]("Cep é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#cidade_coletivo").val() == "") {
                    toastr["error"]("Cidade é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }




                  if($("#bairro_coletivo").val() == "") {
                    toastr["error"]("Bairro é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#rua_coletivo").val() == "") {
                    toastr["error"]("Rua é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 

                 if($("#uf_coletivo").val() == "") {
                    toastr["error"]("UF é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                 if($("#codigo_externo_coletivo").val() == "") {
                    toastr["error"]("Codigo Externo é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;
                 }

                if(!$('input:radio[name=coparticipacao_coletivo]').is(':checked')) {
                    toastr["error"]("Coparticipação é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;  
                } 

                if(!$('input:radio[name=odonto_coletivo]').is(':checked')) {
                    toastr["error"]("Odonto é campo obrigatório")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;  
                } 

                if(
                    $("#faixa-input-0-18_coletivo").val() == "" && 
                    $("#faixa-input-19-23_coletivo").val() == "" && 
                    $("#faixa-input-24-28_coletivo").val() == "" && 
                    $("#faixa-input-29-33_coletivo").val() == "" && 
                    $("#faixa-input-34-38_coletivo").val() == "" && 
                    $("#faixa-input-39-43_coletivo").val() == "" && 
                    $("#faixa-input-44-48_coletivo").val() == "" && 
                    $("#faixa-input-49-53_coletivo").val() == "" && 
                    $("#faixa-input-54-58_coletivo").val() == "" && 
                    $("#faixa-input-59_coletivo").val() == ""
                ) {
                    toastr["error"]("Preencher pelo menos 1 faixa etaria")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    return false;  
                }    

                $.ajax({
                    url:"{{route('contratos.montarPlanos')}}",
                    method:"POST",
                    data:{
                    	"tabela_origem": $("#tabela_origem_coletivo").val(),
						"administradora_id":$("#administradora_coletivo").val(),
						"coparticipacao":$('input:radio[name=coparticipacao_coletivo]:checked').val(),
                        "odonto":$("input:radio[name=odonto_coletivo]:checked").val(),
                    	"faixas" : [{
                            '1' : $('#faixa-input-0-18_coletivo').val(),
                            '2' : $('#faixa-input-19-23_coletivo').val(),
                            '3' : $('#faixa-input-24-28_coletivo').val(),
                            '4' : $('#faixa-input-29-33_coletivo').val(),
                            '5' : $('#faixa-input-34-38_coletivo').val(),
                            '6' : $('#faixa-input-39-43_coletivo').val(),
                            '7' : $('#faixa-input-44-48_coletivo').val(),
                            '8' : $('#faixa-input-49-53_coletivo').val(),
                            '9' : $('#faixa-input-54-58_coletivo').val(),
                            '10' : $('#faixa-input-59_coletivo').val()
                        }]
                    },
                    success(res) {
                        
                        $("#resultado_coletivo").slideUp().html(res).delay(100).slideToggle(100,function(){
                            $('body,html').animate({
                                scrollTop:$(window).scrollTop() + $(window).height(),
                            },1500);
                        });

                        $("body").find('.vigente').datepicker({
                            onSelect: function() { 
                                var dateObject = $(this).datepicker('getDate'); 
                                let dataFormatada = (dateObject.getFullYear() + "-" + adicionaZero(((dateObject.getMonth() + 1))) + "-" + adicionaZero((dateObject.getDate()))) ;     
                                $("form[name='cadastrar_pessoa_fisica_formulario_modal_coletivo']").find("#data_vigencia").attr("value",dataFormatada);   
                            }
                        });


                //         // if(data.plano == "3" || data.plano == "4") {
                //         //     if(data.uf == "GO") {
                //         //         $("body").find('.vigente').datepicker({
                //         //             beforeShowDay: function (d) {
                //         //                 var day = d.getDate();
                //         //                 return [day == 5 || day == 10 || day == 15];
                //         //             }
                //         //         })
                //         //     } else if(data.uf == "MT") {
                //         //         $("body").find('.vigente').datepicker({
                //         //             beforeShowDay: function (d) {
                //         //                 var day = d.getDate();
                //         //                 return [day == 1 || day == 10 || day == 20];
                //         //             }
                //         //         })
                //         //     } else {
                //         //         $("body").find('.vigente').datepicker({
                //         //             beforeShowDay: function (d) {
                //         //                 var day = d.getDate();
                //         //                 return [day == 5 || day == 10 || day == 15];
                //         //             }
                //         //         })
                //         //     }
                //         // } else {
                //         //     $("body").find('.vigente').datepicker()
                //         // }
                        
                        
                        



                    }
                });
            	

                return false;
            });	


			$('body').on('click','.valores-acomodacao',function(e){
                if($("#created_at").val() == "") {
                    $('#mudarDataCriacao').modal('show');
                }
                $(".valores-acomodacao").removeClass('destaque');
                $(this).addClass('destaque');
                let valor_plano = $(this).find('.valor_plano').text().replace("R$ ","");
                let tipo = $(this).find('.tipo').text();
                $("#valor").val(valor_plano);
                $("#acomodacao").val(tipo);
                if(!$(this).hasClass('destaque')) {
                    $('#data_vigencia').val('')
                    $('#data_boleto').val('');
                    $('#valor_adesao').val('');
                }
                
                $('body,html').animate({
                                scrollTop:$(window).scrollTop() + $(window).height(),
                            },1500);

                $("#btn_submit").html("<button type='submit' class='btn btn-block btn-light my-4 salvar_contrato'>Salvar Contrato</button>")
                $('.valores-acomodacao').not('.destaque').each(function(i,e){
                    $(e).find('.vigente').val('')
                    $(e).find('.boleto').val('')
                    $(e).find('.valor_adesao').val('')
                });
                if($(e.target).is('.form-control')) {
                    return;
                } 
            });


            $('form[name="cadastrar_pessoa_fisica_formulario_modal_coletivo"]').on('submit',function(){

                $.ajax({

                    url:"{{route('contratos.store')}}",
                    method:"POST",
                    data:$(this).serialize(),

                    beforeSend:function() {

                         if($("#data_vigencia").val() == "") {
                            toastr["error"]("Preencher o campo data vigencia")
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                            return false;  
                        }

                        if($("#data_boleto").val() == "") {
                            toastr["error"]("Preencher o campo data boleto")
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                            return false;      
                        }

                        if($("#valor_adesao").val() == "") {
                            toastr["error"]("Preencher o campo valor adesão")
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                            return false;            
                        }

                        if(($("#valor_adesao").val() != $("#valor").val()) && $("#desconto_corretor").val() == "") {
                            let valor_t = $("#valor").val().replace(".","").replace(",",".");
                            let valor_a = $("#valor_adesao").val().replace(".","").replace(",",".");
                            let diferenca = valor_t - valor_a;
                            let valor_difrenca = diferenca.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})
                            $(".diferenca_entre_valores").text(valor_difrenca);                            
                            $('#modalDiferencaEntreValores').modal('show');
                            return false;
                        }



                    },
                    success:function(res) {
                        
                        if(res == "contratos") {
                            $(location).prop('href','/admin/contratos?ac=coletivo');
                            return true;
                        } else {
                            $(location).prop('href','/admin/contrato?ac=coletivo');
                            return true;
                        }          

                    }
                });  

                return false;
            });









			function montarValores(data) {
                
                $.ajax({
                    url:"{{route('contratos.montarPlanos')}}",
                    method:"POST",
                    data: data,
                    success(res) {
                         console.log(res);  
                        // $("#resultado_coletivo").slideUp().html(res).delay(100).slideToggle(100,function(){
                        //     $('body,html').animate({
                        //         scrollTop:$(window).scrollTop() + $(window).height(),
                        //     },1500);
                        // });

                        // $("body").find('.vigente').datepicker({
                        //     onSelect: function() { 
                        //         var dateObject = $(this).datepicker('getDate'); 
                        //         let dataFormatada = (dateObject.getFullYear() + "-" + adicionaZero(((dateObject.getMonth() + 1))) + "-" + adicionaZero((dateObject.getDate()))) ;     
                        //         $("form[name='cadastrar_pessoa_fisica_formulario_modal_coletivo']").find("#data_vigencia").attr("value",dataFormatada);   
                        //     }
                        // });


                //         // if(data.plano == "3" || data.plano == "4") {
                //         //     if(data.uf == "GO") {
                //         //         $("body").find('.vigente').datepicker({
                //         //             beforeShowDay: function (d) {
                //         //                 var day = d.getDate();
                //         //                 return [day == 5 || day == 10 || day == 15];
                //         //             }
                //         //         })
                //         //     } else if(data.uf == "MT") {
                //         //         $("body").find('.vigente').datepicker({
                //         //             beforeShowDay: function (d) {
                //         //                 var day = d.getDate();
                //         //                 return [day == 1 || day == 10 || day == 20];
                //         //             }
                //         //         })
                //         //     } else {
                //         //         $("body").find('.vigente').datepicker({
                //         //             beforeShowDay: function (d) {
                //         //                 var day = d.getDate();
                //         //                 return [day == 5 || day == 10 || day == 15];
                //         //             }
                //         //         })
                //         //     }
                //         // } else {
                //         //     $("body").find('.vigente').datepicker()
                //         // }
                        
                        
                        



                    }
                });
                return false;
            }				











		});




	</script>

@stop



