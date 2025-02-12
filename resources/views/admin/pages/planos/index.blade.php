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
                        
                        <th>Editar | Deletar</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
		</div>	
	</div>


	<div class="modal fade" id="cadastrarPlanos" tabindex="-1" aria-labelledby="cadastrarPlanosLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
		    	

			<div class="modal-content" style="background-color:rgba(0,0,0,0.8)">
		      <div class="modal-header">
		        <h5 class="modal-title text-white" id="cadastrarPlanosLabel">Cadastrar Planos</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true" class="text-white" style="color:#FFF;">&times;</span>
		        </button>
		      </div>
		      	<div class="modal-body">
		       	 	<form name="cadastrar_planos" enctype="multipart/form-data" action="{{route('planos.store')}}" method="post">
            			@csrf
            			
            			<div class="form-group">
                    		<label for="nome" class="text-white">Nome:</label>
                    		<input type="text" name="nome" id="nome" class="form-control">	
                    		@if($errors->has('uf'))
                        		<p class="alert alert-danger">{{$errors->first('uf')}}</p>
                    		@endif
                		</div>

		      			</div>
		      			<div class="modal-footer">
			      			<div class="d-flex" style="flex-basis: 100%;">
			      				<button type="button" class="btn btn-danger col-6 mr-1" data-dismiss="modal">Fechar</button>	
			      				<button type="submit" class="btn btn-primary col-6">Cadastrar Planos</button>
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

			


			$('.estilo_btn_plus').on('click',function(){	
				$('#cadastrarPlanos').modal('show');
			});

			

            
            var ta = $(".listargrupo").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_ladingpage">ft><t><"d-flex justify-content-between"lp>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('planos.list') }}",
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
                    {data:"id",name:"id"}
                ],
                "columnDefs": [
                    {
                        "targets": 1,
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
                    $('#title_ladingpage').html("<h4>Planos</h4>");
                }
            });


            $('form[name="cadastrar_planos"]').on('submit',function(){
            	let dados = $(this).serialize();
            	$.ajax({
            		url:"{{route('planos.store')}}",
            		method:"POST",
            		data:dados,
            		success:function(res) {
            			$('#cadastrarPlanos').modal('hide');
            			ta.ajax.reload();
            		}
            	});
            	return false;
            });







		});
	</script>
@stop 

@section('css')
	
		
		<style>
		
		.estilo_btn_plus {background-color:rgba(0,0,0,1);box-shadow:rgba(255,255,255,0.8) 0.1em 0.2em 5px;border-radius: 5px;display: flex;align-items: center;}	

		.estilo_btn_plus i {color: #FFF !important;font-size: 0.7em;padding: 8px;}	

		.estilo_btn_plus:hover {background-color:rgba(255,255,255,0.8);box-shadow:rgba(0,0,0,1) 0.1em 0.2em 5px;}
		
		.estilo_btn_plus:hover i {color: #000 !important;}


		</style>


	
@stop 