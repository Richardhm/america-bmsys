@extends('adminlte::page')
@section('title', 'Tabela de Preços')
@section('content_header')
    <div class="row">
        <div class="col">
            <h1>Tabela de Preços</h1>
        </div>
        <div class="col d-flex justify-content-end">
            <a class="btn btn-warning" href="{{route('tabela.search')}}"><i class="fas fa-search"></i></a>
        </div>
    </div>    
@stop

@section('content')

 <div class="card">
    <div class="card-body"> 

    	<form action="{{route('store.tabela')}}" method="POST">
    		@csrf
    		<div class="form-row">

    			<div class="col-md-3 mb-2">
	                <label for="administradora">Administradora:</label>
	                <select name="administradora" id="administradora" class="form-control">
	                    <option value="">--Escolher a Administradora--</option>
	                    @foreach($administradoras as $aa)
	                        <option value="{{$aa->id}}" {{$aa->id == old('administradora') ? 'selected' : ''}}>{{$aa->nome}}</option>
	                    @endforeach
	                </select>
	                @if($errors->has('administradora'))
	                    <p class="alert alert-danger">{{$errors->first('administradora')}}</p>
	                @endif
            	</div>

            	<div class="col-md-3 mb-2">
                    <label for="planos">Planos:</label>
                    <select name="planos" id="planos" class="form-control">
                        <option value="">--Escolher o Plano--</option>
                        @foreach($planos as $pp)
                        	<option value="{{$pp->id}}" {{$pp->id == old('planos') ? 'selected' : ''}}>{{$pp->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('planos'))
                        <p class="alert alert-danger">{{$errors->first('planos')}}</p>
                    @endif
                </div>

                <div class="col-md-2 mb-2">
                    <label for="">Cidade:</label>
                    <select name="tabela_origem" id="tabela_origem" class="form-control">
                    	<option value="">--Escolher a Cidade--</option>
                        @foreach($tabela_origem as $cc)
                        	<option value="{{$cc->id}}" {{$cc->id == old('tabela_origem') ? 'selected' : ''}}>{{$cc->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('tabela_origem'))
                        <p class="alert alert-danger">{{$errors->first('tabela_origem')}}</p>
                    @endif
                </div>



                <div class="col-md-2 mb-2">
                        <label for="coparticipacao">Coparticipação:</label><br />
                        <select name="coparticipacao" id="coparticipacao" class="form-control">
                            <option value="">--Escolher Coparticipacao--</option>
                            <option value="sim" {{old('coparticipacao') == "sim" ? 'selected' : ''}}>Sim</option>
                            <option value="nao" {{old('coparticipacao') == "nao" ? 'selected' : ''}}>Não</option>
                        </select>
                        @if($errors->has('coparticipacao'))
                            <p class="alert alert-danger">{{$errors->first('coparticipacao')}}</p>
                        @endif
                </div>
                <div class="col-md-2 mb-2">
                        <label for="odonto">Odonto:</label><br />
                        <select name="odonto" id="odonto" class="form-control">
                            <option value="">--Escolher Odonto--</option>
                            <option value="sim" {{old('odonto') == "sim" ? 'selected' : ''}}>Sim</option>
                            <option value="nao" {{old('odonto') == "nao" ? 'selected' : ''}}>Não</option>
                        </select>
                        @if($errors->has('odonto'))
                            <p class="alert alert-danger">{{$errors->first('odonto')}}</p>
                        @endif
                </div>

    		</div>	


			<div class="form-row">
                        
                    <div class="col" style="border-right:2px solid black;">
                        <div class="form-group">
                            @foreach($faixas as $k => $f)
                                <div>
                                    @if($loop->first)
                                        <h6 style="font-weight:bold;text-decoration:underline;">Apartamento</h6>
                                        
                                    @endif
                                    <div class="row mb-2">
                                        <div class="col">
                                            <input type="text" disabled class="" value="{{$f->nome}}" />
                                            <input type="hidden" value="{{$f->id}}" name="faixa_etaria_id_apartamento[]" />
                                            <input type="text" class="valor" placeholder="valor" name="valor_apartamento[]" id="valor" value="{{isset(old('valor_apartamento')[$k]) && !empty(old('valor_apartamento')[$k]) ? old('valor_apartamento')[$k] : ''}}" />
                                            @if($errors->any('valor_apartamento'.$k) && !empty($errors->get('valor_apartamento.'.$k)[0]))
                                                <p class="alert alert-danger">O valor da faixa etaria {{ $f->nome }} e campo obrigatorio</p>
                                            @endif        
                                        </div>    
                                    </div>  
                                    
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div class="col" style="border-right:2px solid black;">
                        <div class="form-group">
                            @foreach($faixas as $k => $f)
                                <div>
                                    @if($loop->first)
                                        <h6 style="font-weight:bold;text-decoration:underline;">Enfermaria</h6>
                                    @endif
                                    <div class="row mb-2">
                                        <div class="col">
                                            <input type="text" disabled class="" value="{{$f->nome}}" />
                                            <input type="hidden" value="{{$f->id}}" name="faixa_etaria_id_enfermaria[]" />
                                            <input type="text" class="valor" placeholder="valor" name="valor_enfermaria[]" id="valor_enfermaria" value="{{isset(old('valor_enfermaria')[$k]) && !empty(old('valor_enfermaria')[$k]) ? old('valor_enfermaria')[$k] : ''}}" />
                                            @if($errors->any('valor_enfermaria'.$k) && !empty($errors->get('valor_enfermaria.'.$k)[0]))
                                                <p class="alert alert-danger">O valor da faixa etaria {{ $f->nome }} e campo obrigatorio</p>
                                            @endif        
                                        </div>    
                                    </div>  
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                                @foreach($faixas as $k => $f)
                                    <div>
                                        @if($loop->first)
                                            <h6 style="font-weight:bold;text-decoration:underline;">Ambulatorial</h6>
                                        @endif
                                        <div class="row mb-2">
                                            <div class="col">
                                                <input type="text" disabled class="" value="{{$f->nome}}" />
                                                <input type="hidden" value="{{$f->id}}" name="faixa_etaria_id_ambulatorial[]" />
                                                <input type="text" class="valor" placeholder="valor" name="valor_ambulatorial[]" id="valor_ambulatorial" value="{{isset(old('valor_ambulatorial')[$k]) && !empty(old('valor_ambulatorial')[$k]) ? old('valor_ambulatorial')[$k] : ''}}" />
                                                @if($errors->any('valor_ambulatorial'.$k) && !empty($errors->get('valor_ambulatorial.'.$k)[0]))
                                                    <p class="alert alert-danger">O valor da faixa etaria {{ $f->nome }} e campo obrigatorio</p>
                                                @endif        
                                            </div>    
                                        </div>  
                                    </div>
                                @endforeach
                            </div>
                        </div>





            </div>            		


            <button class="btn btn-primary btn-block mt-3">Cadastrar</button>

    	</form>	


    </div>
</div>        	







@stop

@section('js')
	<script src="{{asset('js/jquery.mask.min.js')}}"></script>
	<script>
		$(function(){
			$('.valor').mask("#.##0,00", {reverse: true});
		});
	</script>
	
@stop 