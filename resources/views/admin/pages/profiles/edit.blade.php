@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h3>Editar Perfil</h3>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}   
        </div>
    @endif

    <div class="card">
        <div class="card-header">
          
            @if($user->image)
                @php
                    $t = new \App\Support\Thumb();
                @endphp   
                <div class="text-center">
                    <img src="{{\Illuminate\Support\Facades\Storage::url($t->makes($user->image,150,150))}}" alt="{{$user->name}}" class="rounded-circle">
                </div> 
                
            @else
                
            @endif
        </div>
        <div class="card-body">

        @if($errors->any())
            @foreach($errors->all() as $e)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{$e}}
                </div>
            @endforeach
        @endif

        <form action="{{route('profile.setUser')}}" method="post" enctype="multipart/form-data">
            @csrf            
            <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="name">Nome*</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome" value="{{$user->name ?? old('nome')}}">
                    
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cpf">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" value="{{$user->cpf ?? old('cpf')}}">
                    
                </div>
            </div>

            <!-- <div class="form-group">
                <label for="image">Foto:</label>
                <input type="file" class="form-control" id="image" name="image">
               
            </div>
             -->
            
            <div class="form-row">
                <div class="col-md-9 mb-3">
                    <label for="endereco">Endereco:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereco" value="{{$user->endereco ?? old('endereco')}}">
                   
                </div>
                <div class="col-md-3 mb-3">
                    <label for="numero">Numero:</label>
                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Numero" value="{{$user->numero ?? old('numero')}}">
                   
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="cidade">Cidade:</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" value="{{$user->cidade ?? old('cidade')}}">
                    
                </div>
                <div class="col-md-2 mb-3">
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
                <div class="col-md-4 mb-3">
                    <label for="celular">Celular:</label>
                    <input type="text" class="form-control" id="celular" name="celular" placeholder="(XX) X XXXX-XXXX" value="{{$user->celular ?? old('celular')}}">
                   
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="password">Senha:*</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Senha" value="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation">Confirmar Senha:*</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Senha Novamente" value="">
                </div>        
            </div>

            <input class="btn btn-primary btn-block" type="submit" value="Editar User">   
        </form>
        </div>
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}" />    
    <link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.css')}}" />    
@stop
@section('js')
<script src="{{asset('js/jquery.mask.min.js')}}"></script>
<script src="{{asset('vendor/select2/js/select2.min.js')}}"></script>
<script>
        $(function(){
            $('#cpf').mask('000.000.000-00', {reverse: true});
            $('#celular').mask('(00) 0 0000-0000');
            $('#estado').select2({
                theme: 'bootstrap4',
            });
        });
    </script>
@endsection
