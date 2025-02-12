@extends('adminlte::page')
@section('title', 'Tabela Origem')
@section('plugins.Datatables', true)


@section('content_header')
	<h3 class="text-white">
		<button class="estilo_btn_plus">
			<i class="fas fa-plus"></i>	
		</button>
		
	</h3>
@stop

@section('content')
	
	<div class="card shadow" style="background-color:#123449;color:#FFF;">
		<div class="card-body" style="box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;">
			<table class="table table-sm listargrupo">
                <thead>
                    <tr class="text-white">
                        <th>Nome</th>
                        <th>Estado</th>
                        <th>Editar | Deletar</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</div>	
	</div>


	<div class="modal fade" id="cadastrarTabelaOrigem" tabindex="-1" aria-labelledby="cadastrarTabelaOrigemLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
		    	

			<div class="modal-content" style="background-color:rgba(0,0,0,0.8)">
		      <div class="modal-header">
		        <h5 class="modal-title text-white" id="cadastrarTabelaOrigemLabel">Cadastrar Tabela Origem</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true" class="text-white" style="color:#FFF;">&times;</span>
		        </button>
		      </div>
		      	<div class="modal-body">
		       	 	<form name="cadastrar_tabela_origem" enctype="multipart/form-data" action="" method="post">
            			@csrf
            			
            			<div class="form-group">
                    		<label for="uf" class="text-white">Escolha Um Estado:</label>
                    		<select id="uf" name="uf" class="form-control select2-single">
                        		<option value=""></option>
                    		</select>
                    		@if($errors->has('uf'))
                        		<p class="alert alert-danger">{{$errors->first('uf')}}</p>
                    		@endif
                		</div>


                   		<div class="form-group">
                    		<label for="nome">Escolha Uma Cidade:</label>
                    		<select id="nome" name="nome" class="form-control select2-single">
                    		</select>
		                    @if($errors->has('nome'))
		                        <p class="alert alert-danger">{{$errors->first('nome')}}</p>
		                    @endif
                		</div>


		      			</div>
		      			<div class="modal-footer">
			      			<div class="d-flex" style="flex-basis: 100%;">
			      				<button type="button" class="btn btn-danger col-6 mr-1" data-dismiss="modal">Fechar</button>	
			      				<button type="submit" class="btn btn-primary col-6">Cadastrar Tabela Origem</button>
			      			</div>
		      			</div>	      
		      		</form>
		    	</div>
		</div>
	</div>







@stop

@section('js')
	<script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
	<script>
		$(function(){

			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

			$.getJSON("{{asset('js/estados_cidades.json')}}", function (data) {
            	var items = [];
            	var options = '<option value="">Escolha um Estado</option>';	
	            $.each(data, function (key, val) {
	                options += '<option value="' + val.nome + '">' + val.nome + '</option>';
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
                	$("#nome").html(options_cidades);
            	}).change();		
        	});


			$('.estilo_btn_plus').on('click',function(){	
				$('#cadastrarTabelaOrigem').modal('show');
			});

			$("body").find('#uf').select2({
				dropdownParent: $('#cadastrarTabelaOrigem .modal-content'),
				theme: 'bootstrap4',
			});
			$("body").find('#nome').select2({
				dropdownParent: $('#cadastrarTabelaOrigem .modal-content'),
            	theme: 'bootstrap4',
        	});

            
            var ta = $(".listargrupo").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_ladingpage">ft><t><"d-flex justify-content-between"lp>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('tabela_origem.list') }}",
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
                    {data:"nome",name:"nome"},
                    {data:"uf",name:"uf"},
                    {data:"id",name:"id"}
                ],
                "columnDefs": [
                    {
                        "targets": 2,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).html(
                            	`
                            		<button class='editar_admin btn btn-info' data-id='${cellData}'>
                 						<i class="fas fa-edit"></i>	           			
                            		</button> | 
                            		<button class='deletar_admin btn btn-danger' data-id='${cellData}'>
                 						<i class="fas fa-trash"></i>	           			
                            		</button> 
                            	`
                            	)
                        }   
                    },
               ],
                
                "initComplete": function( settings, json ) {
                    $('#title_ladingpage').html("<h4>Tabela Origem</h4>");
                }
            });


            $('form[name="cadastrar_tabela_origem"]').on('submit',function(){
            	let dados = $(this).serialize();
            	$.ajax({
            		url:"{{route('tabela_origem.store')}}",
            		method:"POST",
            		data:dados,
            		success:function(res) {
            			$('#cadastrarTabelaOrigem').modal('hide');
            			ta.ajax.reload();
            		}
            	});
            	return false;
            });







		});
	</script>
@stop 

@section('css')
	<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}" />    
	<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.css')}}" />


	<style>
		
		.select2-results .select2-highlighted {
    		background: #3ea211 !important;
		}


		.select2-container--default .select2-results__option--highlighted[aria-selected] {
   			background-color: #5897fb !important;
   			color: black !important;
		}


		.estilo_btn_plus {background-color:rgba(0,0,0,1);box-shadow:rgba(255,255,255,0.8) 0.1em 0.2em 5px;border-radius: 5px;display: flex;align-items: center;}	

		.estilo_btn_plus i {color: #FFF !important;font-size: 0.7em;padding: 8px;}	

		.estilo_btn_plus:hover {background-color:rgba(255,255,255,0.8);box-shadow:rgba(0,0,0,1) 0.1em 0.2em 5px;}
		
		.estilo_btn_plus:hover i {color: #000 !important;}


	</style>
	
@stop