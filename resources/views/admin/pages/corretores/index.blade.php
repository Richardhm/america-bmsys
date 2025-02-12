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
	
	

	<!--Modal-->
	<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cadastrar Colaborador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" name="cadastrar_colaborador" enctype="multipart/form-data" class="invoice-repeater">
            @csrf
        
    	        
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="name">Nome*</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome" value="{{old('name')}}">
                    @if($errors->has('name'))
                        <p class="alert alert-danger">{{$errors->first('name')}}</p>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cpf">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" value="{{old('cpf')}}">
                    @if($errors->has('cpf'))
                        <p class="alert alert-danger">{{$errors->first('cpf')}}</p>
                    @endif
                    @if(session('errorcpf'))
                        <p class="alert alert-danger">{{ session('errorcpf') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="image">Foto:</label>
                <input type="file" class="form-control" id="image" name="image">
               
            </div>
            
            
            <div class="form-row">
                <div class="col-md-9 mb-3">
                    <label for="endereco">Endereco:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereco" value="{{old('endereco')}}">
                    @if($errors->has('endereco'))
                        <p class="alert alert-danger">{{$errors->first('endereco')}}</p>
                    @endif
                </div>
                <div class="col-md-3 mb-3">
                    <label for="numero">Numero:</label>
                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Numero" value="{{old('numero')}}">
                    @if($errors->has('numero'))
                        <p class="alert alert-danger">{{$errors->first('numero')}}</p>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="cidade">Cidade:</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" value="{{old('cidade')}}">
                    @if($errors->has('cidade'))
                        <p class="alert alert-danger">{{$errors->first('cidade')}}</p>
                    @endif
                </div>
                <div class="col-md-3 mb-3">
                    <label for="estado" class="control-label">Estado:</label>
                    <select id="estado" name="estado" class="form-control select2-single">
                        <option value="">Escolha o estado</option>
                        <option value="AC" {{old('estado') == "AC" ? 'selected' : ''}}>Acre</option>
                        <option value="AL" {{old('estado') == "AL" ? 'selected' : ''}}>Alagoas</option>
                        <option value="AP" {{old('estado') == "AP" ? 'selected' : ''}}>Amapá</option>
                        <option value="AM" {{old('estado') == "AM" ? 'selected' : ''}}>Amazonas</option>
                        <option value="BA" {{old('estado') == "BA" ? 'selected' : ''}}>Bahia</option>
                        <option value="CE" {{old('estado') == "CE" ? 'selected' : ''}}>Ceará</option>
                        <option value="DF" {{old('estado') == "DF" ? 'selected' : ''}}>Distrito Federal</option>
                        <option value="ES" {{old('estado') == "ES" ? 'selected' : ''}}>Espírito Santo</option>
                        <option value="GO" {{old('estado') == "GO" ? 'selected' : ''}}>Goiás</option>
                        <option value="MA" {{old('estado') == "MA" ? 'selected' : ''}}>Maranhão</option>
                        <option value="MT" {{old('estado') == "MT" ? 'selected' : ''}}>Mato Grosso</option>
                        <option value="MS" {{old('estado') == "MS" ? 'selected' : ''}}>Mato Grosso do Sul</option>
                        <option value="MG" {{old('estado') == "MG" ? 'selected' : ''}}>Minas Gerais</option>
                        <option value="PA" {{old('estado') == "PA" ? 'selected' : ''}}>Pará</option>
                        <option value="PB" {{old('estado') == "PB" ? 'selected' : ''}}>Paraíba</option>
                        <option value="PR" {{old('estado') == "PR" ? 'selected' : ''}}>Paraná</option>
                        <option value="PE" {{old('estado') == "PE" ? 'selected' : ''}}>Pernambuco</option>
                        <option value="PI" {{old('estado') == "PI" ? 'selected' : ''}}>Piauí</option>
                        <option value="RJ" {{old('estado') == "RJ" ? 'selected' : ''}}>Rio de Janeiro</option>
                        <option value="RN" {{old('estado') == "RN" ? 'selected' : ''}}>Rio Grande do Norte</option>
                        <option value="RS" {{old('estado') == "RS" ? 'selected' : ''}}>Rio Grande do Sul</option>
                        <option value="RO" {{old('estado') == "RO" ? 'selected' : ''}}>Rondônia</option>
                        <option value="RR" {{old('estado') == "RR" ? 'selected' : ''}}>Roraima</option>
                        <option value="SC" {{old('estado') == "SC" ? 'selected' : ''}}>Santa Catarina</option>
                        <option value="SP" {{old('estado') == "SP" ? 'selected' : ''}}>São Paulo</option>
                        <option value="SE" {{old('estado') == "SE" ? 'selected' : ''}}>Sergipe</option>
                        <option value="TO" {{old('estado') == "TO" ? 'selected' : ''}}>Tocantins</option>
                    </select>        
                    
                </div>
                <div class="col-md-3 mb-3">
                    <label for="celular">Celular:</label>
                    <input type="text" class="form-control" id="celular" name="celular" placeholder="(XX) X XXXXX-XXXX" value="{{old('celular')}}">
                    @if($errors->has('celular'))
                        <p class="alert alert-danger">{{$errors->first('celular')}}</p>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="password">Senha:*</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Senha" value="{{old('password')}}">
                    @if($errors->has('password'))
                        <p class="alert alert-danger">{{$errors->first('password')}}</p>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">Email:*</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email')}}">
                    @if($errors->has('email'))
                        <p class="alert alert-danger">{{$errors->first('email')}}</p>
                    @endif
                </div>        
            </div>
           	
            <div class="form-group">
            	<label for="cargo">Cargo</label>
            	@foreach($cargos as $c)
            		<div class="d-flex">
            			<input type="radio" id="cargo_{{$c->nome}}" name="cargo" value="{{$c->id}}">{{$c->nome}}
            		</div>
            	@endforeach
            </div>



            
            <button class="btn btn-primary btn-block" type="submit">Cadastrar</button>
           </form>
      </div>
      
    </div>
  </div>
</div>



	<!--Fim Modal-->




@stop 


@section('js')
	<script>
		$(function(){
			$(".estilo_btn_plus").on('click',function(){
				$('#exampleModalLong').modal('show')
			});

			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


			let image = ""
            $("#image").on('change',function(e){
            	image = e.target.files;
            });


			$("form[name='cadastrar_colaborador']").on('submit',function(e){
            	var fd = new FormData();
		        fd.append('file',image[0]);		        
		        fd.append('nome',$('#name').val());
		        fd.append('endereco',$('#endereco').val());
		        fd.append('numero',$('#numero').val());
		        fd.append('cidade',$('#cidade').val());
		        fd.append('estado',$('#estado').val());
		        fd.append('celular',$('#celular').val());
		        fd.append('password',$('#password').val());
		        fd.append('email',$('#email').val());
		        fd.append('cargo',$('input[name="cargo"]:checked').attr('checked',true).val());
                fd.append('cpf',$("#cpf").val());

            	$.ajax({
            		url:"{{route('corretores.store')}}",
            		method:"POST",
            		data:fd,
            		contentType: false,
           			processData: false,
            		success:function(res){
            			console.log(res);
            			// if(res == "sucesso") {
            			// 	$('#cadastrarAdministradora').modal('hide');
            			// 	ta.ajax.reload();
            			// } else {

            			// }
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