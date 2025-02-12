@extends('adminlte::page')
@section('title', 'Corretora')
@section('content_header')
	<h3 class="text-white">Dados Corretora:</h3>
@stop

@section('content')
	
    @if($errors->all())
        @foreach($errors->all() as $error)
            <p class="alert alert-danger">{{$error}}</p>
        @endforeach
    @endif


    <!-- PDF -->
    <div class="container_pdf">

    <div class="card" style="background-color:#123449;color:#eee;">
        @if (session('alterado'))
            <div class="alert text-center" style="background-color:rgba(0,0,0,0.5)">
                {{ session('alterado') }}
            </div>
        @endif

        

        
        <form method="POST" enctype="multipart/form-data" action="{{route('corretora.store')}}">
            @csrf
            <h3 class="ml-2"></h3>
            <div class="form-row ml-1 mr-1">
                
                <div class="col-3">    
                    <input type="file" id="logo" name="logo" accept="image/*">
                    <div class="imageContainer">
                        <img src="{{isset($corretora->logo) && $corretora->logo != null ? asset('storage/'.$corretora->logo) : 

                            asset('camera.png')}}" alt="Camera" id="imgAlt">
                    </div>
                </div>
                
                <div class="col-9">
                    <div class="form-row mt-3">
                        <div class="col-6">
                            Nome:  
                            <input type="text" id="nome" name="nome" class="form-control" value="{{old('nome') ?? $corretora->nome}}">
                        </div>
                        <div class="col-6">
                            Telefone:  <input type="text" id="telefone" placeholder="(XX) X XXXX-XXXX" name="telefone" class="form-control" value="{{old('telefone') ?? $corretora->telefone}}">
                        </div>
                    </div>
                    
                    <div class="form-row mt-3">
                        <div class="col-6">
                            Email:  <input type="email" id="email" placeholder="email@email.com" name="email" class="form-control" value="{{old('email') ?? $corretora->email}}">
                        </div>
                        <div class="col-6">
                            Site: <input type="url" id="site" name="site" placeholder="https://www.google.com.br" class="form-control" value="{{old('site') ?? $corretora->site}}">
                        </div>        
                    </div>

                    <div class="form-row mt-3">
                        <div class="col-6">
                            Instagram: <input type="text" id="instagram" placeholder="Instagram" name="instagram" class="form-control" value="{{old('instagram') ?? $corretora->instagram}}" />
                        </div>
                        <div class="col-6">
                            Endereço: <input type="text" id="endereco" name="endereco" placeholder="Localização" class="form-control" value="{{old('endereco') ?? $corretora->endereco}}">
                        </div>    
                    </div>                                    
                </div> 
            </div>
        <div>
            <h3 class="ml-2 mt-2">Dados do Orçamento:</h3>
            <div class="form-row ml-2 mr-2">
                <div class="col-3">
                    Consultas Eletivas:  <input type="text" id="consultas_eletivas" value="{{old('consultas_eletivas') ?? $corretora->consultas_eletivas}}" name="consultas_eletivas" class="form-control" placeholder="Consultas Eletivas:">
                </div>
                <div class="col-3">
                    Consultas Urgencias: <input type="text" id="consultas_urgencia" value="{{old('consultas_urgencia') ?? $corretora->consultas_urgencia}}" name="consultas_urgencia" class="form-control" placeholder="Consultas Urgencias:">     
                </div>
                <div class="col-3">
                    Exames Simples:  <input type="text" id="exames_simples" value="{{old('exames_simples') ?? $corretora->exames_simples}}" name="exames_simples" class="form-control" placeholder="Exames Simples:">
                </div>
                <div class="col-3">
                    Exames Complexos:  <input type="text" id="exames_complexos" value="{{old('exames_complexos') ?? $corretora->exames_complexos}}" name="exames_complexos" class="form-control" placeholder="Exames Complexos:">
                </div>
            </div>
            <div class="mt-3">
                <h3 style="margin-right:0;margin-left:10px;">Observação coletivo:</h3>
                <div class="form-group row" style="margin-right:12px;margin-left:10px;">
                    <input type="text" name="linha_01_coletivo" id="linha_01_coletivo" class="form-control col-11 input_contar" placeholder="Linha01: " maxlength="130" value="{{old('linha_01_coletivo') ?? $corretora->linha_01_coletivo}}">  
                    <div class="cont col-1 d-flex align-items-center">
                        <span class="value_min">0</span>
                        <span class="value_max">/130</span>
                    </div>
                </div>
                <div class="form-group row" style="margin-right:12px;margin-left:10px;">
                    <input type="text" name="linha_02_coletivo" id="linha_02_coletivo" class="form-control col-11 input_contar" placeholder="Linha02: " maxlength="130" value="{{old('linha_02_coletivo') ?? $corretora->linha_02_coletivo}}">
                    <div class="cont col-1 d-flex align-items-center">
                        <span class="value_min">0</span>
                        <span class="value_max">/130</span>
                    </div>
                </div>
                <div class="form-group row" style="margin-right:12px;margin-left:10px;">                
                    <input type="text" name="linha_03_coletivo" id="linha_03_coletivo" class="form-control col-11 input_contar" placeholder="Linha03: " maxlength="130" value="{{old('linha_03_coletivo') ?? $corretora->linha_03_coletivo}}">                
                    <div class="cont col-1 d-flex align-items-center">
                        <span class="value_min">0</span>
                        <span class="value_max">/130</span>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <h3 style="margin-left:5px;">Observação Individual:</h3>
                <div class="form-group row" style="margin-right:12px;margin-left:10px;">   
                    <input type="text" name="linha_01_individual" id="linha_01_individual" class="form-control col-11 input_contar" placeholder="Linha01: " maxlength="130" value="{{old('linha_01_individual') ?? $corretora->linha_01_individual}}">                
                    <div class="cont col-1 d-flex align-items-center">
                        <span class="value_min">0</span>
                        <span class="value_max">/130</span>
                    </div>
                </div>
                <div class="form-group row" style="margin-right:12px;margin-left:10px;">   
                    <input type="text" name="linha_02_individual" id="linha_02_individual" class="form-control col-11 input_contar" placeholder="Linha02: " maxlength="130" value="{{old('linha_02_individual') ?? $corretora->linha_02_individual}}">                
                    <div class="cont col-1 d-flex align-items-center">
                        <span class="value_min">0</span>
                        <span class="value_max">/130</span>
                    </div>
                </div>

                <div class="form-group row" style="margin-right:12px;margin-left:10px;">   
                    <input type="text" name="linha_03_individual" id="linha_03_individual" class="form-control col-11 input_contar" placeholder="Linha03: " maxlength="130" value="{{old('linha_03_individual') ?? $corretora->linha_03_individual}}">                
                    <div class="cont col-1 d-flex align-items-center">
                        <span class="value_min">0</span>
                        <span class="value_max">/130</span>
                    </div>
                </div>
            </div>

            <div class="row mr-2 ml-2">
                <input type="submit" value="{{$corretora->nome != '' ? 'Alterar' : 'Cadastrar'}}" class="btn btn-block mb-2 mr-2" style="background-color:rgba(0,0,0,0.8);color:#FFF;">
            </div>
        </div>
    </form>
    </div>
    </div>
    <!-- Fim PDF -->


    

@stop


@section('js')
	<script src="{{asset('js/jquery.mask.min.js')}}"></script>
	<script>
		$(function(){
			$('#telefone').mask('(00) 0 0000-0000');
			$('#consultas_eletivas').mask("#.##0,00", {reverse: true});
			$('#consultas_urgencia').mask("#.##0,00", {reverse: true});
			$('#exames_simples').mask("#.##0,00", {reverse: true});
			$('#exames_complexos').mask("#.##0,00", {reverse: true});

            $("#imgAlt").on('click',function(){
                $("#logo").click();
            });

            $("#logo").on('change',function(e){
                if(e.target.files.length <= 0) {
                    return;
                }
                let reader = new FileReader();
                reader.onload = () => {
                    $("#imgAlt").attr('src',reader.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });

            $(".input_contar").on('keyup',function(){
                let contar = $(this).val().length;
                $(this).closest("div").find('.value_min').text(contar);
            }); 
            $(".input_contar").trigger('keyup');


		});
	</script>
@stop 




@section('css')
    <style>

        .linkar:hover {
            cursor:pointer;
        }


        hr {
            background-color: #eee;
        }

        input[type='file'] {
            display:none;
        }

        .ocultar {
            display:none;
        }

        .imageContainer {
            max-width: 200px;
            background-color: #eee;
            border:5px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin:25px auto;
        }

        .imageContainer img {
            width:100%;
            padding:5px;
            cursor: pointer;
            transition: background .3s;
            border-radius: 50%;
        }

        .imageContainer:hover {
            background-color: rgb(180,180,180);
            border:5px solid #111;
        }
        .cont {
            background-color: rgba(0,0,0,0.5);
            border-radius:5px;
            display: flex;
            justify-content: center;
        }

        ul.menu {
            
            display: flex;
            list-style: none;
            margin:0;
            padding:0;
            /* background-color: red; */
        }

        .menu li {
            color:#FFF;
            display: flex;
            flex-basis: 120px;
            background-color: rgba(0,0,0,0.8);
            padding:10px;
            justify-content: center;
            
        }
        .destaque {
            border-top: 1px solid #ccc;
            border-left:1px solid #ccc;
            border-right:1px solid #ccc;
            background-color: rgba(0,0,0,0.2) !important;
        }
        .create_grupo {
            border:none;
            border-radius:5px;
        }



    </style>    
@stop
