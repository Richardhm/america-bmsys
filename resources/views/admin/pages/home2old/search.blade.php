@extends('adminlte::page')
@section('title', 'Pesquisar')
@section('content_header')
    <!-- <h1>Resultado pesquisa</h1> -->



@stop

@section('content_top_nav_right')
    <li class="rounded border border-white">
        <a class="text-white nav-link" href="{{route('orcamento.search.home')}}">Tabelas</a>
    </li>

    <li class="bg-white rounded">
        <a class="text-white nav-link" href="{{route('orcamento.index')}}">Orçamento</a>
    </li>

    <li class="bg-white rounded">
        <a class="text-white nav-link" href="{{route('home.administrador.consultar')}}">Consultar</a>
    </li>






@stop






@section('content')

    <input type="hidden" name="administradora_id" id="administradora_id">
    <!-----CARD SEARCH------->

    <div class="container_full_administradoras">
        @foreach($administradoras as $adm)
            <div class="d-flex administradoras_container justify-content-center" id="{{$adm->id}}">
                <img src="{{asset($adm->logo)}}" alt="{{$adm->nome}}" width="80%;" height="80%;"></p>
            </div>
        @endforeach
    </div>

    <div class="tabela_preco_cidades">
        <select name="select_cidade" id="select_cidade" class="form-control">
            <option value="">--Escolher a Cidade</option>
            @foreach($cidades as $ci)
                <option value="{{$ci->id}}">{{$ci->nome}}</option>
            @endforeach
        </select>
    </div>

    <div id="aquiPlano"></div>














@stop

@section('css')
    <style>
        /* table tbody tr:nth-child(even) {
            background-color:#696969;
            color:#FFF;
        } */

        #resultado {
            display:flex;
            flex-wrap:wrap;
        }



        .tabela_preco_cidades {
            display:none;
        }

        .card {
            flex-basis:32%;
            padding:7px;
            background-color:#123449;
            color:#FFF;
            margin-right:1%;
        }

        .form-search {
            display:none;
        }

        .btns {
            display: flex;
            justify-content: end;
            padding:5px 0;
        }

        #select_cidade {

            flex-basis:20%;
        }

        .administradoras_container {
            flex-basis:22%;
            background-color:#FFF;
            padding:10px;
            margin-bottom:10px;
            border-radius:5px;
        }

        .btn-search {
            border:none;
            background-color: white;
        }

        .container_full_administradoras {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;

        }

        @media (max-width: 1200px) {
            #resultado {
                justify-content:space-between;
            }
            .card {
                flex-basis:48%;
                /*display: none;*/
            }
        }

        @media (max-width: 800px) {
            #resultado {
                justify-content:center;
            }
            .card {
                flex-basis:100%;
                /*display: none;*/
            }
            .card table {
                width: 100% !important;
            }
            .container_full_administradoras {
                /*flex-wrap: wrap !important;             */
            }
            .administradoras_container {
                /*display: none;*/
                /*flex-basis:100% !important; */
            }
            #select_cidade {

                flex-basis:100%;
            }
        }

        @media (max-width: 600px) {
            .container_full_administradoras {
                flex-wrap: wrap !important;
            }
            .administradoras_container {
                flex-basis:45%;
            }
            table tbody td {
                font-size:0.8em;
            }

            table tbody td {
                font-size:0.74em;
            }
            table thead td {
                font-size:0.74em;
            }
            #select_cidade {

                flex-basis:100%;
            }
        }
    </style>
@stop



@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>
        $(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#select_cidade").on("change",function(){
                let cidade = $(this).val();
                let administradora = $("#administradora_id").val();

                $("#administradora_id").val(administradora);
                $.ajax({
                    url:"{{route('tabela.preco.resposta.cidade')}}",
                    method:"POST",
                    data:
                        "administradora="+administradora+
                        "&cidade="+cidade
                    ,
                    success:function(res) {
                        if(res == "error_vazio") {
                            $("#aquiPlano").html("<p class='alert alert-danger'>Sem tabela de preço para essa cidade =/</p>");

                        } else {
                            $("#aquiPlano").html(res);
                        }


                    }
                });

            });


            $("body").on('click','.administradoras_container',function(){
                let id = $(this).attr("id");
                $(".tabela_preco_cidades").css({"display":"flex","margin-bottom":"10px"});
                let tabela_origem = $("#select_cidade").val();

                $("#administradora_id").val(id);
                $.ajax({
                    url:"{{route('tabela.preco.resposta.cidade')}}",
                    method:"POST",
                    data:
                        "administradora="+id+
                        "&cidade="+tabela_origem
                    ,
                    success:function(res) {
                        if(res == "error_vazio") {
                            $("#aquiPlano").html("<p class='alert alert-danger'>Sem tabela de preço para essa cidade =/</p>");

                        } else {
                            $("#aquiPlano").html(res);
                        }
                    }
                });
            });


        });
    </script>
@stop
