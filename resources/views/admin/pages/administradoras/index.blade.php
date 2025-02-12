@extends('adminlte::page')
@section('title', 'Corretora')
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


	<div class="modal fade" id="cadastrarAdministradora" tabindex="-1" aria-labelledby="cadastrarAdministradoraLabel" aria-hidden="true">
		<div class="modal-dialog">
		    	

			<div class="modal-content" style="background-color:rgba(0,0,0,0.8);color:#FFF;">
		      <div class="modal-header">
		        <h5 class="modal-title" id="cadastrarAdministradoraLabel">Cadastrar Administradora</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true" class="text-white" style="color:#FFF;">&times;</span>
		        </button>
		      </div>
		      	<div class="modal-body">
		       	 	<form name="cadastrar_admin" enctype="multipart/form-data" action="" method="post">
            			@csrf
            			<div class="form-group">
                			<label for="nome">Nome:</label>
                			<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="">
                    	</div>
                   		<div class="form-group">
                			<div class="col-md-12 mb-3">
                    			<label for="logo">Logo:</label>
                    			<input type="file" class="form-control" id="logo" name="logo">
                			</div>
                    	</div>
		      			</div>
		      			<div class="modal-footer">
			      			<div class="d-flex" style="flex-basis: 100%;">
			      				<button type="button" class="btn btn-danger col-6 mr-1" data-dismiss="modal">Fechar</button>	
			      				<button type="submit" class="btn btn-primary col-6">Cadastrar Administradora</button>
			      			</div>
		      			</div>	      
		      		</form>
		    	</div>
		</div>
	</div>


@stop


@section('js')

	<script>
        $(function(){
            
        	$('.estilo_btn_plus').on('click',function(){
				$('#cadastrarAdministradora').modal('show');
			});



            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let logo = ""
            $("#logo").on('change',function(e){
            	logo = e.target.files;
            });





            var ta = $(".listargrupo").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_ladingpage">ft><t><"d-flex justify-content-between"lp>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('administradoras.list') }}",
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
                    $('#title_ladingpage').html("<h4>Administradoras</h4>");
                }
            });

             $("form[name='cadastrar_admin']").on('submit',function(e){
            	var fd = new FormData();
		        fd.append('file',logo[0]);		        
		        fd.append('nome',$('#nome').val());
            	$.ajax({
            		url:"{{route('administradoras.store')}}",
            		method:"POST",
            		data:fd,
            		contentType: false,
           			processData: false,
            		success:function(res){
            			if(res == "sucesso") {
            				$('#cadastrarAdministradora').modal('hide');
            				ta.ajax.reload();
            			} else {

            			}
            		}
            	});
            	return false;
            });



        });    
    </script>        



@endsection

@section('css')
	<style>
		
		.estilo_btn_plus {background-color:rgba(0,0,0,1);box-shadow:rgba(255,255,255,0.8) 0.1em 0.2em 5px;border-radius: 5px;display: flex;align-items: center;}	

		.estilo_btn_plus i {color: #FFF !important;font-size: 0.7em;padding: 8px;}	

		.estilo_btn_plus:hover {background-color:rgba(255,255,255,0.8);box-shadow:rgba(0,0,0,1) 0.1em 0.2em 5px;}
		
		.estilo_btn_plus:hover i {color: #000 !important;}


	</style>

@endsection




