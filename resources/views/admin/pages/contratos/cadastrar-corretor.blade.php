@extends('adminlte::page')
@section('title', 'Cadastrar Individual')
@section('plugins.jqueryUi', true)
@section('plugins.Toastr', true)


@section('content_header')
	<h2 class="text-white">Plano Individual</h2>
@stop


@section('content')

	<div style="background-color:#123449;border-radius:5px;padding:10px 5px;">
		<form action="" method="post" class="px-3" name="cadastrar_pessoa_fisica_formulario_individual" id="cadastrar_pessoa_fisica_formulario_individual">
            @csrf              
            
            <input type="hidden" name="tipo_cadastro" value="corretor_cadastro">


            <!-- Primeiro Linha -->
            <div class="d-flex">          
                <input type="hidden" name="users_individual" id="users_individual" value="{{Auth::user()->id}}">
                <div style="flex-basis:13%;margin-right: 1%;">
                    <div class="form-group">
                        <span for="tabela_origem" class="text-white">Tabela Origem:</span>
                        <select name="tabela_origem_individual" id="tabela_origem_individual" class="form-control form-control-sm change_valores">
                            <option value="">--Tabela Origem--</option>
                            @foreach($origem_tabela as $o)
                                <option value="{{$o->id}}">{{$o->nome}}</option>
                            @endforeach
                        </select>   
                       
                    </div>
                </div>


                <div style="flex-basis:25%">
                    <div class="form-group">
                        <span for="nome" class="text-white">Titular:</span>
                        <input type="text" name="nome_individual" id="nome_individual" class="form-control form-control-sm" placeholder="Nome" value="">
                        
                    </div>
                </div>

                <div style="flex-basis:10%;margin:0% 1%;">
                    <div class="form-group">
                        <span for="cpf" class="text-white">CPF:</span>
                        <input type="text" name="cpf_individual" id="cpf_individual" class="form-control form-control-sm" value="{{old('cpf')}}" placeholder="XXX.XXXX.XXX-XX">
                        <div class="errorcpf"></div>
                        
                    </div>
                </div>

                <div style="flex-basis:10%;margin-right:1%;">
                    <div class="form-group">
                        <span for="data_nascimento" class="text-white">Data Nascimento:</span>
                        <input type="date" name="data_nascimento_individual" value="{{old('data_nascimento')}}" id="data_nascimento_individual" class="form-control  form-control-sm">
                        
                    </div>
                </div>

                <div style="flex-basis:16%;margin-right:1%;">
                    <div class="form-group">
                        <span for="email" class="text-white">Email:</span>
                        <input type="email" name="email_individual" id="email_individual" placeholder="Email" class="form-control  form-control-sm" value="">
                        
                    </div>
                </div>    

                <div class="form-group" style="flex-basis:11%;margin-right: 1%;">
                    <span for="telefone" class="text-white">Celular:</span>
                    <input type="text" name="celular_individual" id="celular_individual" value="{{old('celular_individual')}}" placeholder="Celular" class="form-control  form-control-sm" value="">
                </div>
                
                <div class="form-group" style="flex-basis:11%;">
                    <span for="telefone" class="text-white">Telefone:</span>
                    <input type="text" name="telefone_individual" id="telefone_individual" value="{{old('telefone_individual')}}" placeholder="Telefone" class="form-control  form-control-sm" value="">
                </div>



            </div>    
            <!-- Fim Primeiro Linha -->

            <!-- Segunda Linha -->                
            <div class="d-flex">   

                <div style="flex-basis:8%;">
                    <div class="form-group">
                        <span for="cep" class="text-white">CEP:</span>
                        <input type="text" name="cep_individual" id="cep_individual" value="{{old('cep')}}" placeholder="CEP" class="form-control  form-control-sm" value="">
                        
                    </div>
                </div>



                <div class="form-group" style="flex-basis:13%;margin:0% 1%;">
                    <span for="rua" class="text-white">Cidade:</span>
                    <input type="text" name="cidade_origem_individual" id="cidade_origem_individual" value="{{old('cidade_origem')}}" placeholder="Cidade" class="form-control  form-control-sm" value="">
                    
                </div>



                <div class="form-group" style="flex-basis:12%;">
                    <span for="bairro" class="text-white">Bairro:</span>
                    <input type="text" name="bairro_individual" id="bairro_individual" value="{{old('bairro')}}" placeholder="Bairro" class="form-control  form-control-sm" value="">
                </div>

               


                <div class="form-group" style="flex-basis:12%;margin:0 1%;">
                    <span for="rua" class="text-white">Rua:</span>
                    <input type="text" name="rua_individual" id="rua_individual" value="{{old('rua')}}" placeholder="Logradouro(Rua)" class="form-control  form-control-sm" value="">
                    <div class="errorlogradouro"></div>
                </div>

                 <div class="form-group" style="flex-basis:15%;">
                    <span for="bairro" class="text-white">Complemento:</span>
                    <input type="text" name="complemento_individual" id="complemento_individual" value="{{old('complemento')}}" placeholder="Complemento(Opcional)" class="form-control  form-control-sm" value="">
                    <div class="errorcomplemento"></div>
                </div>

                

                <div class="form-group" style="flex-basis:5%;margin:0 1%;">
                    <span for="uf" class="text-white">UF:</span>
                    <input type="text" name="uf_individual" id="uf_individual" value="{{old('uf')}}" placeholder="UF" class="form-control  form-control-sm" value="">
                    <div class="erroruf"></div>
                </div>


               

                <div style="flex-basis:10%;">
                    <div class="form-group">
                        <span for="codigo_externo" class="text-white">Codigo Externo:</span>
                        <input type="text" name="codigo_externo_individual" id="codigo_externo_individual_cadastrar" value="{{old('codigo_externo')}}" class="form-control  form-control-sm" placeholder="COD.">
                        <div class="errorcodigo"></div>
                    </div>
                </div>  

                <div style="flex-basis:9%;margin:0 1% 0 1%">
                    <div class="form-group d-flex justify-content-center flex-column">
                        <span class="text-white">Coparticipação:</span>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-light" id="coparticipacao_sim" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="coparticipacao_individual" id="coparticipacao_radio_sim_cadastro"  value="sim" {{old('coparticipacao') == "sim" ? 'checked' : ''}}> Sim
                            </label>
                            <label class="btn btn-outline-light" id="coparticipacao_nao" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="coparticipacao_individual" id="coparticipacao_radio_nao_cadastro" value="nao" {{old('coparticipacao') == "nao" ? 'checked' : ''}}> Não
                            </label>
                            
                        </div>
                        <div class='errorcoparticipacao'></div>
                    </div>
                </div>        

                <div style="flex-basis:9%;">
                    <div class="form-group  d-flex justify-content-center flex-column">
                        <span for="odonto" class="text-white">Odonto:</span>
                        
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-light" id="odonto_sim" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="odonto_individual" id="odonto_radio_sim_cadastro" value="sim" {{old('odonto') == "sim" ? 'checked' : ''}}> Sim
                            </label>
                            <label class="btn btn-outline-light" id="odonto_nao" style="padding:0.21rem 0.75rem;">
                                <input type="radio" name="odonto_individual" id="odonto_radio_nao_cadastro" value="nao" {{old('odonto') == "nao" ? 'checked' : ''}}> Não
                            </label>
                            
                        </div>
                        <div class='errorodonto'></div>
                    </div>
                </div>   



            </div>
            <!-- Fim Segunda Linha -->


            <div class="d-flex">
                <div style="flex-basis: 10%;">
                    <input type="checkbox" id="dependente_individual" name="dependente_individual"><span class="text-white">Responsável</span>    
                </div>
                
                <div style="flex-basis: 90%;" class="d-none" id="container_responsavel">
                    <div class="d-flex">   
                        <div style="flex-basis:30%;margin-right:1%;display:flex;">
                            
                                <span style="flex-basis:30%;" for="responsavel_financeiro_individual_cadastro" class="text-white">Responsável:</span>
                                <input style="flex-basis:70%" type="text" name="responsavel_financeiro_individual_cadastro" id="responsavel_financeiro_individual_cadastro" value="" class="form-control  form-control-sm" placeholder="Nome do Responsavel">
                                
                            
                        </div>  
                        <div style="flex-basis:70%;display: flex;">
                            
                                <span style="flex-basis:17%;" for="cpf_financeiro_individual_cadastro" class="text-white">CPF Responsável:</span>
                                <input style="flex-basis:30%" type="text" name="cpf_financeiro_individual_cadastro" id="cpf_financeiro_individual_cadastro" value="" class="form-control  form-control-sm" placeholder="CPF Responsavel">
                                
                            
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
                                    <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-0-18" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_0_18" name="faixas_etarias[1]" value="{{isset($colunas) && in_array(1,$colunas) ? $faixas[array_search(1, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-0-18_individual" class="text-center font-weight-bold flex-fill faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="d-flex justify-content-center align-items-center plus" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      


                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">19-23:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-19-23" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_19_23" name="faixas_etarias[2]" value="{{isset($colunas) && in_array(2,$colunas) ? $faixas[array_search(2, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-19-23_individual" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="d-flex align-items-center justify-content-center plus" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">24-28:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="d-flex justify-content-center align-items-center minus bg-danger" id="faixa-24-28" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_24_28" name="faixas_etarias[3]" value="{{isset($colunas) && in_array(3,$colunas) ? $faixas[array_search(3, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-24-28_individual" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="plus d-flex justify-content-center align-items-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">29-33:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus d-flex justify-content-center align-items-center bg-danger" id="faixa-29-33" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" data-change="change_faixa_29_33" name="faixas_etarias[4]" value="{{isset($colunas) && in_array(4,$colunas) ? $faixas[array_search(4, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-29-33_individual" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="plus  d-flex justify-content-center align-items-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">34-38:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus d-flex justify-content-center align-items-center bg-danger" id="faixa-34-38" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[5]" data-change="change_faixa_34_38" value="{{isset($colunas) && in_array(5,$colunas) ? $faixas[array_search(5, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-34-38_individual" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus d-flex align-items-center justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>              

                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">39-43:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus d-flex justify-content-center align-items-center bg-danger" id="faixa-39-43" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[6]" data-change="change_faixa_39_43" value="{{isset($colunas) && in_array(6,$colunas) ? $faixas[array_search(6, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-39-43_individual" class="text-center font-weight-bold flex-fill w-25 faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" class="text-center" />
                                    <button type="button" class="plus d-flex justify-content-center align-items-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">44-48:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus d-flex justify-content-center align-items-center bg-danger" id="faixa-44-48" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[7]" data-change="change_faixa_44_48" value="{{isset($colunas) && in_array(7,$colunas) ? $faixas[array_search(7, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-44-48_individual" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus d-flex justify-content-center align-items-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;margin:0 10px;">
                            <span for="" class="text-white">49-53:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-49-53" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[8]" data-change="change_faixa_49_53" value="{{isset($colunas) && in_array(8,$colunas) ? $faixas[array_search(8, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-49-53_individual" class="text-center align-items-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;max-height: 30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;margin:0 10px 0 0;">
                            <span for="" class="text-white">54-58:</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">
                                    <button type="button" class="minus d-flex align-items-center justify-content-center bg-danger" id="faixa-54-58" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    <input type="tel" name="faixas_etarias[9]" data-change="change_faixa_54_58" value="{{isset($colunas) && in_array(9,$colunas) ? $faixas[array_search(9, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-54-58_individual"  class="text-center font-weight-bold faixas_etarias d-flex" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" />
                                    <button type="button" class="plus d-flex align-items-center justify-content-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                    </button>
                                </div>
                            </div>  
                        </div>      

                        <div style="flex-basis:10%;">
                            <span for="" class="text-white">59+</span>
                            <div class="border border-white rounded">
                                <div class="d-flex content">

                                    <button type="button" class="minus d-flex justify-content-center align-items-center bg-danger" id="faixa-59" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                        <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                    </button>
                                    
                                    <input type="tel" data-change="change_faixa_59" name="faixas_etarias[10]" value="{{isset($colunas) && in_array(10,$colunas) ? $faixas[array_search(10, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-59_individual" class="text-center font-weight-bold faixas_etarias d-flex" style="border:none;width:40%;font-size:1.2em;max-height:30px;" value="" step="1" min="0" />
                                    
                                    <button type="button" class="plus d-flex justify-content-center align-items-center" style="border:none;background:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
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
                        <button id="mostrar_plano_individual" class="w-100">
                        	Mostrar Planos
                        </button>
                    </div>
                </div>
                <div id="resultado_individual">
                </div>    
            </form>

	</div>
	
@stop


@section('js')
	<script src="{{asset('js/jquery.mask.min.js')}}"></script>   
	<script>
		$(function(){

            $("#email_individual").on('keyup',(e) => {
                $('#email_individual').val($('#email_individual').val().toLowerCase());
            });

            

			function adicionaZero(numero){
                if (numero <= 9) 
                    return "0" + numero;
                else
                    return numero; 
            }

			$('#cnpj').mask('00.000.000/0000-00');
            $('#telefone_individual').mask('(00) 0000-0000');
            $('#celular_individual').mask('(00) 0 0000-0000');
            $('#celular').mask('(00) 0 0000-0000');
            $('#taxa_adesao').mask("#.##0,00", {reverse: true});
            $('#valor_plano').mask("#.##0,00", {reverse: true});
            $('#valor_total').mask("#.##0,00", {reverse: true});
            $('#valor_boleto').mask("#.##0,00", {reverse: true});
            $('#valor_plano_saude').mask("#.##0,00", {reverse: true});

            $('#valor_plano_saude').mask("#.##0,00", {reverse: true});
            $('#valor_plano_odonto').mask("#.##0,00", {reverse: true});
            $('#cpf_individual').mask('000.000.000-00');

            $('#cpf_financeiro_individual_cadastro').mask('000.000.000-00');            
            
            $('#cpf_coletivo').mask('000.000.000-00');  
            $('#cep_individual').mask('00000-000');          
            $('#cep_coletivo').mask('00000-000');      

			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    

			$("body").on('change','#dependente_individual',function(){
                if($(this).is(':checked')) {
                    $("#container_responsavel").removeClass('d-none');
                } else {
                    $("#container_responsavel").addClass('d-none');
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
                //$("#data_boleto").val(data_boleto);
            });

            $("body").on('change','input[name="adesao"]',function(){
                let valor_adesao = $(this).val();
                $(this).closest('form').find('#valor_adesao').val(valor_adesao);
                
            });

			$("body").find('form[name="cadastrar_pessoa_fisica_formulario_individual"]').on("click","#mostrar_plano_individual",function(){
                
                if($("#users_individual").val() == "") {
                    toastr["error"]("Corretor é campo obrigatório")
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

                if($("#tabela_origem_individual").val() == "") {
                    toastr["error"]("Tabela Origem é campo obrigatório")
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

                if($("#nome_individual").val() == "") {
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

                 if($("#cpf_individual").val() == "") {
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

                 if($("#data_nascimento_individual").val() == "") {
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

                 if($("#email_individual").val() == "") {
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

                 if($("#celular_individual").val() == "") {
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

                if($("#tabela_origem_individual").val() == "") {
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
                                 
                 if($("#cep_individual").val() == "") {
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

                  if($("#bairro_individual").val() == "") {
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

                 if($("#rua_individual").val() == "") {
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

                  if($("#cidade_origem_individual").val() == "") {
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

                 if($("#uf_individual").val() == "") {
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

                 if($("#codigo_externo_individual_cadastrar").val() == "") {
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

                if(!$('input:radio[name=coparticipacao_individual]').is(':checked')) {
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

                if(!$('input:radio[name=odonto_individual]').is(':checked')) {
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
                    $("#faixa-input-0-18_individual").val() == "" && 
                    $("#faixa-input-19-23_individual").val() == "" && 
                    $("#faixa-input-24-28_individual").val() == "" && 
                    $("#faixa-input-29-33_individual").val() == "" && 
                    $("#faixa-input-34-38_individual").val() == "" && 
                    $("#faixa-input-39-43_individual").val() == "" && 
                    $("#faixa-input-44-48_individual").val() == "" && 
                    $("#faixa-input-49-53_individual").val() == "" && 
                    $("#faixa-input-54-58_individual").val() == "" && 
                    $("#faixa-input-59_individual").val() == ""
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
                    url:"{{route('contratos.montarPlanosIndividual')}}",
                    method:"POST",
                    data:{
                    	"tabela_origem": $("#tabela_origem_individual").val(),
						"administradora_id":4,
						"odonto":$('input:radio[name=odonto_individual]:checked').val(),
                        "coparticipacao":$("input:radio[name=coparticipacao_individual]:checked").val(),
                    	"faixas" : [{
                            '1' : $('#faixa-input-0-18_individual').val(),
                            '2' : $('#faixa-input-19-23_individual').val(),
                            '3' : $('#faixa-input-24-28_individual').val(),
                            '4' : $('#faixa-input-29-33_individual').val(),
                            '5' : $('#faixa-input-34-38_individual').val(),
                            '6' : $('#faixa-input-39-43_individual').val(),
                            '7' : $('#faixa-input-44-48_individual').val(),
                            '8' : $('#faixa-input-49-53_individual').val(),
                            '9' : $('#faixa-input-54-58_individual').val(),
                            '10' : $('#faixa-input-59_individual').val()
                        }]
                    },
                    success:function(res) {
                        
                        $("#resultado_individual").slideUp().html(res).delay(100).slideToggle(100,function(){
                            $('body,html').animate({
                                scrollTop:$(window).scrollTop() + $(window).height(),
                            },1500);
                        });

                        $("body").find('.vigente').datepicker({
                            onSelect: function() { 
                                var dateObject = $(this).datepicker('getDate'); 
                                let dataFormatada = (dateObject.getFullYear() + "-" + adicionaZero(((dateObject.getMonth() + 1))) + "-" + adicionaZero((dateObject.getDate()))) ;     
                                $("form[name='cadastrar_pessoa_fisica_formulario_individual']").find("#data_vigencia").attr("value",dataFormatada);   
                            }
                        });
                    }  
                });   


                return false;
            });

			 /** Quando clicar no card pegar os campos valor do plano e tipo(Apartamento,Enfermaria...) */
            $('body').on('click','.valores-acomodacao',function(e){
                
                let valor_plano = $(this).find('.valor_plano').text().replace("R$ ","");
                
                let tipo = $(this).find('.tipo').text();
                $("#valor").val(valor_plano);
                $("#acomodacao").val(tipo);
                if(!$(this).hasClass('destaque')) {
                    $('#data_vigencia').val('')
                    $('#data_boleto').val('');
                    $('#valor_adesao').val('');
                }
                $(".valores-acomodacao").removeClass('destaque');
                $(this).addClass('destaque');
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

            $("#cep_individual").change(function(){
                let   cep = $(this).val().replace("-","");
                const url = `https://viacep.com.br/ws/${cep}/json`;
                const options = {method: "GET",mode: "cors",
                    headers: {'content-type': 'application/json;charset=utf-8'}
                }
                fetch(url,options).then(response => response.json()).then(
                    data => {       
                        $("#rua_individual").val(data.logradouro);
                        $("#bairro_individual").val(data.bairro);
                        $("#uf_individual").val(data.uf);
                        $("#cidade_origem_individual").val(data.localidade);
                    }                    
                )
                if($(this).val() != "") {
                    $(".errorcep").html('');
                }   
            });


            $('form[name="cadastrar_pessoa_fisica_formulario_individual"]').on('submit',function(){
                $.ajax({
                    url:"{{route('individual.store')}}",
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
                    },
                    
                    success:function(res) {
                        
                        if(res == "contratos") {
                            $(location).prop('href','/admin/contratos');
                            return true;
                        } else {
                            $(location).prop('href','/admin/contrato');
                            return true;
                        }  
                    }
                })
                return false;
            });

			 function montarValoresIndividual(data) {
                
                $.ajax({
                    url:"{{route('contratos.montarPlanosIndividual')}}",
                    method:"POST",
                    data: data,
                    success(res) {
                        $("#resultado_individual").slideUp().html(res).delay(100).slideToggle(100,function(){
                            $('#cadastrarIndividualModal').animate({
                                scrollTop:$(window).scrollTop() + $(window).height(),
                            },1500);
                        });

                        $("body").find('.vigente').datepicker({
                            onSelect: function() { 
                                var dateObject = $(this).datepicker('getDate'); 
                                let dataFormatada = (dateObject.getFullYear() + "-" + adicionaZero(((dateObject.getMonth() + 1))) + "-" + adicionaZero((dateObject.getDate()))) ;     
                                $("form[name='cadastrar_pessoa_fisica_formulario_individual']").find("#data_vigencia").attr("value",dataFormatada);   
                            }
                        });

                    }
                });    
            }


		});


	</script>

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
