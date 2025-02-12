@extends('adminlte::page')
@section('title', 'Cadastro Empresarial')
@section('plugins.jqueryUi', true)
@section('content_header')
    <div class="d-flex align-items-center">
        <a href="{{url('admin/financeiro?ac=empresarial')}}" style="border-radius:50%;border:1px solid black;padding:5px 8px;margin-right:5px;background-color:black;color:aliceblue;"><i class="fas fa-arrow-left fa-lg"></i></a>
        <h2 class="text-white">Plano Empresarial</h2>
    </div>
	
@stop


@section('content')
	<div style="background-color:#123449;border-radius:5px;padding:10px 5px;">
	
		<form action="{{route('contratos.storeEmpresarial.financeiro')}}" method="post" class="px-3" name="cadastrar_dados_empresarial" id="cadastrar_dados_empresarial">
            @csrf     
            <div style="padding:0;margin:8px 0;">         
            	<!-- Primeiro Linha -->
            <div class="d-flex">
                <div style="flex-basis:11%;margin-right:1%;">
                    <div>
                        <span for="user_id" class="text-white" style="font-size:0.875em;">Vendedor:</span>
                        <select required name="user_id" id="user_id" class="form-control  form-control-sm">
                            <option value="" class="text-center">--Vendedor--</option>
                            @foreach($users as $u)
                                <option value="{{$u->id}}">{{$u->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="flex-basis:10%">
                    <div>
                        <span for="plano_id" class="text-white" style="font-size:0.875em;">Plano:</span>
                        <select required class="form-control  form-control-sm" id="plano_id" name="plano_id">
                            <option value="" class="text-center">--Plano--</option>
                            @foreach($planos_empresarial as $p)
                                <option value="{{$p->id}}">{{$p->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="flex-basis:15%;margin:0% 1%;">
                    <div>
                        <span for="tabela_origens_id" class="text-white" style="font-size:0.875em;">Origem Tabela:</span>
                        <select  class="form-control  form-control-sm" id="tabela_origens_id" name="tabela_origens_id" required>
                            <option value="" class="text-center">--Origem Tabela--</option>
                            @foreach($origem_tabela as $o)
                                <option value="{{$o->id}}">{{$o->nome}}</option>
                            @endforeach
                        </select>           
                    </div>
                </div>
                <div style="flex-basis:12%;">
                    <div>
                        <span for="codigo_corretora" class="text-white" style="font-size:0.875em;">Codigo Corretora:</span>
                        <input type="text" name="codigo_corretora" value="{{old('codigo_corretora')}}" required id="codigo_corretora" class="form-control  form-control-sm" placeholder="COD. Corretora">
                    </div>
                </div>
                <div style="flex-basis:14%;margin:0% 1%;">
                    <div>
                        <span for="cnpj" class="text-white" style="font-size:0.875em;">CNPJ:</span>
                        <input type="text" name="cnpj" id="cnpj" class="form-control  form-control-sm" placeholder="CNPJ" required>         
                    </div>
                </div>
                <div style="flex-basis:21%;margin-right:1%;">
                    <span for="razao_social" class="text-white" style="font-size:0.875em;">Razão Social:</span>
                    <input type="text" name="razao_social" required id="razao_social" value="{{old('razao_social')}}" placeholder="Razão Social" class="form-control  form-control-sm" value="">
                </div>
                <div style="flex-basis:12%;margin-right:1%;">
                    <div>
                        <span for="codigo_externo" class="text-white" style="font-size:0.875em;">Codigo Externo:</span>
                        <input type="text" name="codigo_externo" value="{{old('codigo_externo')}}" required id="codigo_externo" class="form-control  form-control-sm" placeholder="COD. Externo">
                    </div>
                </div>

                <div style="flex-basis:8%;">
                    <span for="quantidade_vidas" class="text-white" style="font-size:0.875em;">Vidas:</span>
                    <input type="number" name="quantidade_vidas" required id="quantidade_vidas" value="{{old('quantidade_vidas')}}" placeholder="QTE" class="form-control  form-control-sm" value="">
                </div>

            </div>    
            <!-- Fim Primeiro Linha -->

            </div>


            <div style="padding:0;margin:8px 0;">
            	
        		<!-- Primeiro Linha -->            
				<div class="d-flex">
									
					<div style="flex-basis:15%;">
	                    <div>
	                        <span for="responsavel" class="text-white" style="font-size:0.875em;">Responsável:</span>
	                        <input type="text" name="responsavel" id="responsavel" class="form-control  form-control-sm" placeholder="Responsável" required>         
	                    </div>
	                </div>

	                <div style="flex-basis:10%;margin:0% 1%;">
	                    <div>
	                        <span for="telefone" class="text-white" style="font-size:0.875em;">Telefone:</span>
	                        <input type="text" name="telefone" id="telefone" class="form-control  form-control-sm" placeholder="Telefone">         
	                    </div>
	                </div>

	                <div style="flex-basis:10%;">
	                    <div>
	                        <span for="celular" class="text-white" style="font-size:0.875em;">Celular:</span>
	                        <input type="text" name="celular" id="celular" class="form-control form-control-sm" placeholder="Celular" required>         
	                    </div>
	                </div>

	                <div style="flex-basis:15%;margin:0% 1%;">
	                    <div>
	                        <span for="email" class="text-white" style="font-size:0.875em;">Email:</span>
	                        <input type="text" name="email" id="email" class="form-control form-control-sm" placeholder="Email" required>         
	                    </div>
	                </div>


					<div style="flex-basis:10%;margin-right: 1%;">
	                    <div>
	                        <span for="uf" class="text-white" style="font-size:0.875em;">UF:</span>
	                        <select id="uf" name="uf" class="form-control form-control-sm select2-single" required>
                                <option value=""></option>
                            </select>         
	                    </div>
	                </div>

	                <div style="flex-basis:15%;margin-right:1%;">
	                    <div>
	                        <span for="cidade" class="text-white" style="font-size:0.875em;">Cidade:</span>
	                        <select id="cidade" name="cidade" class="form-control form-control-sm select2-single" required></select>    
	                    </div>
	                </div>

	                <div style="flex-basis:15%;margin-right:1%;">
	                    <div>
	                        <span for="plano_contrado" class="text-white" style="font-size:0.875em;">Plano Contratado:</span>
	                        <select name="plano_contrado" id="plano_contrado" class="form-control form-control-sm" required>
	                        	<option class="text-center" value="">--Plano Contratado--</option>
	                        	<option value="1">C/ Copart + Odonto</option>
	                        	<option value="2">C/ Copart Sem Odonto</option>
	                        	<option value="3">Sem Copart + Odonto</option>
	                        	<option value="4">Sem Copart Sem Odonto</option>
	                        </select>
	                    </div>
	                </div>

                    <div style="flex-basis:8%;">
	                    <div>
	                        <span for="created_at" class="text-white" style="font-size:0.875em;">Data Cadastrado:</span>
	                        <input type="date" required name="created_at" id="created_at" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm">
	                    </div>
	                </div>







				</div>

                <!-- <div style="flex-basis:8%;">   
                    <div>
                        <span for="created_at" class="text-white" style="font-size:0.875em;">Data de Cadastro:</span>
                        <input type="date" name="created_at" id="created_at" value="">
                    </div>    
                </div> -->

                    

				<!-- Fim Primeiro Linha -->            


            </div>
           
            <div>
            	<div class="d-flex" style="padding:0;margin:8px 0;"> 

                    <div class="form-group" style="flex-basis:11%;margin-right:1%;">
                        <span for="valor_plano_saude" class="text-white" style="font-size:0.875em;">Valor Plano Saúde:</span>
                        <input type="text" name="valor_plano_saude" required id="valor_plano_saude" value="{{old('valor_plano_saude')}}" placeholder="Valor Plano Saúde" class="form-control form-control-sm" value="">                    
                    </div>                

                    <div class="form-group" style="flex-basis:11%;margin-right:1%;">
                        <span for="valor_plano_odonto" class="text-white" style="font-size:0.875em;">Valor Plano Odonto:</span>
                        <input type="text" name="valor_plano_odonto" required id="valor_plano_odonto" value="{{old('valor_plano_odonto')}}" placeholder="Valor Plano Odonto" class="form-control form-control-sm form-control-sm" value="">                    
                    </div>

                    <div class="form-group" style="flex-basis:11%;margin-right:1%;">
                        <span for="taxa_adesao" class="text-white" style="font-size:0.875em;">Taxa Adesão:</span>
                        <input type="text" name="taxa_adesao" required id="taxa_adesao" value="{{old('taxa_adesao')}}" placeholder="Taxa Adesão" class="form-control form-control-sm" value="">                    
                    </div>

                    <div class="form-group" style="flex-basis:11%;margin-right:1%;">
                        <span for="valor_boleto" class="text-white" style="font-size:0.875em;">Valor Boleto:</span>
                        <input type="text" name="valor_boleto" required id="valor_boleto" value="{{old('valor_boleto')}}" placeholder="Valor Boleto" class="form-control form-control-sm" value="">                    
                    </div>

                    <div class="form-group" style="flex-basis:6%;margin-right:1%;">
                        <span for="vencimento_boleto" class="text-white" style="font-size:0.875em;">Vencimento:</span>
                        <input type="date" name="vencimento_boleto" required id="vencimento_boleto" value="{{old('vencimento_boleto')}}" placeholder="Vencimento Boleto" class="form-control form-control-sm" value="">                    
                    </div>

                    <div class="form-group" style="flex-basis:10%;margin-right:1%;">
                        <span for="codigo_saude" class="text-white" style="font-size:0.875em;">Codigo Saude:</span>
                        <input type="text" name="codigo_saude" id="codigo_saude" value="{{old('codigo_saude')}}" placeholder="Codigo Saude" class="form-control form-control-sm" value="">                    
                    </div>

                    <div class="form-group" style="flex-basis:10%;margin-right:1%;">
                        <span for="codigo_odonto" class="text-white" style="font-size:0.875em;">Codigo Odonto:</span>
                        <input type="text" name="codigo_odonto" id="codigo_odonto" value="{{old('codigo_odonto')}}" placeholder="Codigo Odonto" class="form-control form-control-sm" value="">                    
                    </div>
                
                    <div class="form-group" style="flex-basis:10%;margin-right:1%;">
                        <span for="senha_cliente" class="text-white" style="font-size:0.875em;">Senha Cliente:</span>
                        <input type="text" name="senha_cliente" id="senha_cliente" value="{{old('senha_cliente')}}" class="form-control form-control-sm" placeholder="Senha Cliente">
                    </div>
                
                    <div style="flex-basis:6%;">
                        <div class="form-group">
                            <span for="data_boleto" class="text-white" style="font-size:0.875em;">Data 1º Boleto:</span>
                            <input type="date" name="data_boleto" required id="data_boleto" value="{{old('data_boleto')}}" class="form-control form-control-sm" placeholder="Data Boleto">
                        </div>
                    </div>
                
                </div>
            </div>
            <!-- Segunda Linha -->   
            
            


            
            <!-- Fim Segunda Linha -->
            <input type="submit" class="btn btn-block btn-primary" value="Cadastrar">
            <!--Faixas Etarias--->
            </form>





	</div>	
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}" />    
    <link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.css')}}" />
@stop    




@section('js')



    <script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>

	<script src="{{asset('js/jquery.mask.min.js')}}"></script>
	<script>
		$(function(){

            $("#email").on('keyup',(e) => {
                $('#email').val($('#email').val().toLowerCase());
            });

			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    

			$('#cnpj').mask('00.000.000/0000-00');
            //$('#telefone_individual').mask('0000-0000');
            //$('#celular_individual').mask('(00) 0 0000-0000');

            $('#telefone').mask('(00) 0000-0000');
            $('#celular').mask('(00) 0 0000-0000');
            $('#taxa_adesao').mask("#.##0,00", {reverse: true});

            $('#desconto_corretor').mask("#.##0,00", {reverse: true});
            $('#desconto_corretora').mask("#.##0,00", {reverse: true});
            $('#taxa_adesao').mask("#.##0,00", {reverse: true});

         //    $('#valor_plano').mask("#.##0,00", {reverse: true},translation: {
         //    	"%": {
         //        	pattern: /\%/,
         //        	optional: true
         //    	}
        	// });

            $("#valor_plano").mask("#.##0,00", {reverse: true});
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

            $.getJSON("{{asset('js/estados_cidades.json')}}", function (data) {
                var items = [];
                var options = '<option value="">UF</option>';    
                $.each(data, function (key, val) {
                    options += '<option value="' + val.sigla + '">' + val.nome + '</option>';
                });                 
                $("#uf").html(options);                        
                $("#uf").change(function () {               
                    var options_cidades = '';
                    var str = "";                   
                    $("#uf option:selected").each(function () {
                        str += $(this).text();
                    });
                    $.each(data, function (key, val) {
                        if(val.nome == str) {                           
                            $.each(val.cidades, function (key_city, val_city) {
                                options_cidades += '<option value="' + val_city + '">' + val_city + '</option>';
                            });                         
                        }
                    });
                    $("#cidade").html(options_cidades);
                }).change();        
            });
		});
	</script>	

@stop 