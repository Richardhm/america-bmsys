@extends('adminlte::page')

@section('title', 'Consultar')

@section('content_header')
@stop

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

@section('content_top_nav_right')
   <li class="rounded border border-white">
        <a class="text-white nav-link" href="{{route('home.administrador.consultar')}}">Consultar</a>
    </li>

    <li class="bg-white rounded">
        <a class="text-white nav-link" href="{{route('orcamento.index')}}">Orçamento</a>
    </li>
    <li class="nav-item bg-white rounded">
        <a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabelas</a>
    </li>
   


@stop

@section('content')
    <div>
        <form action="" method="POST">
            @csrf
            <label for="cpf" class="text-dark">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF">
            <input type="submit" value="Consultar" class="btn btn-block btn-info">
        </form>
    </div>
    <div id="resultado" class="mt-2">
    </div>
@stop
@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>

        $(function(){
            $('#cpf').mask('000.000.000-00');
            function TestaCPF(strCPF) {
                var Soma;
                var Resto;
                Soma = 0;

                if (strCPF == "00000000000") return false;
                for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
                Resto = (Soma * 10) % 11;
                if ((Resto == 10) || (Resto == 11))  Resto = 0;
                if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
                Soma = 0;
                for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
                Resto = (Soma * 10) % 11;
                if ((Resto == 10) || (Resto == 11))  Resto = 0;
                if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
                return true;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('form').on('submit',function(){
                let cpf = $("#cpf").val();
                var load = $(".ajax_load");

                if(!TestaCPF($("#cpf").val().replace(/[^0-9]/g,''))) {
                    load.fadeOut(200);
                    $("#resultado").html('<p class="alert alert-danger text-center w-100">CPF inválido</p>');
                    return false;
                }

                $.ajax({
                    url:"{{route('consultar.carteirinha')}}",
                    method:"POST",
                    data:"cpf="+cpf,
                    beforeSend: function () {
                        load.fadeIn(200).css("display", "flex");
                    },
                    success:function(res) {
                        if(res == "error") {
                            load.fadeOut(200);
                            $("#resultado").html('<p class="alert alert-danger text-center w-100">Sem Resultado para este CPF.</p>');
                            return false;
                        } else {
                            load.fadeOut(200,function(){
                                $("#resultado").html(res);
                            });
                        }


                    }
                });
                return false;
            });




        })







    </script>
@stop

@section('css')
    <style>

        .parcela_parcela_um {
            margin-right:5px;
        }

        .card_info {flex-basis:49%;background-color:#123449;border-radius:5px;padding:5px;}
        .historico_corretor {flex-basis:49%;background-color:#123449;color:#FFF;border-radius:5px;max-height:500px;overflow-y:scroll;}

        .line01 {display: flex;}
        .administradora {flex-basis:27%;}
        .tipo_plano {flex-basis:34%;margin:0 0%;}
        .status {flex-basis:42%;}

        .line02 {display: flex;}
        .cliente {flex-basis:40%;}
        .data_nascimento {flex-basis:28%;margin:0 1%;}
        .codigo_externo {flex-basis:30%;}

        .line03 {display:flex;}
        .cpf {flex-basis:33%;}
        .celular {flex-basis:33%;margin:0 1%;display:flex;align-items: flex-end;}
        .telefone {flex-basis:33%;}

        .line04 {display: flex;}
        .email {flex-basis:40%;}
        .numero_registro {flex-basis:25%;margin:0 1%;}
        .carteirinha {flex-basis:35%;}

        .line05 {display: flex;}
        .cep {flex-basis:18%;}
        .cidade {flex-basis:35%;margin:0 1%;}
        .bairro {flex-basis:45%;}


        .line06 {display: flex;}
        .rua {flex-basis:60%;}
        .complemento {flex-basis:29%;margin:0 1%;}
        .uf {flex-basis:10%;margin-right:1%;}

        .line07 {display: flex;}
        .data_contrato {flex-basis:23%;}
        .valor_contrato {flex-basis:23%;margin:0 1%;}
        .valor_adesao {flex-basis:23%;}

        .data_boleto {flex-basis:20%;margin-left:1%;}
        .vidas {flex-basis:8%;margin-left:1%;}

        .line08 {display: flex;}
        .data_vigencia {flex-basis:23%;margin-right:1%;}
        .rede_plano {flex-basis:35%;margin:0 1%;}
        .segmentacao {flex-basis:39%;}

        .line09 {display: flex;}
        .plano {flex-basis:70%;margin-right:1%;}
        .tipo_plano_acomodacao {flex-basis:29%;}


        @media (max-width: 850px) {
           .card_info {
                flex-basis:99%;
           }
           .historico_corretor {
                flex-basis:99%;
                margin-top: 10px;
           }

           td,th {
                font-size:0.875em;
           }
        }

        @media (max-width: 400px) {

            .line01 {flex-wrap: wrap;}
            .administradora {flex-basis:49% !important;margin-right: 1%;}
            .tipo_plano {flex-basis:49% !important;margin:0 0%;}
            .status {flex-basis:100% !important;}

            .line02 {flex-wrap: wrap;}
            .cliente {flex-basis:100% !important;}
            .data_nascimento {flex-basis:49% !important;margin-right:1% !important;}
            .codigo_externo {flex-basis:49% !important;}

            .line03 {flex-wrap: wrap;}
            .cpf {flex-basis:100% !important;}
            .celular {flex-basis:49%;margin-right:2% !important;}
            .telefone {flex-basis:46% !important;}

            .line04 {flex-wrap:wrap;}
            .email {flex-basis:100% !important;}
            .numero_registro {flex-basis:49% !important;margin:0 1% 0 0;}
            .carteirinha {flex-basis:49% !important;}

            .line05 {flex-wrap:wrap;}
            .cep {flex-basis: 48% !important;}
            .cidade {flex-basis: 49% !important;}
            .bairro {flex-basis:100% !important;}

            .line06 {flex-wrap:wrap;}
            .rua {flex-basis: 100% !important;margin:0 0 !important;}
            .complemento {flex-basis:70% !important;margin:0 2% 0 0 !important;}
            .uf {flex-basis:25% !important;margin:0 0 !important;}


            .line07 {flex-wrap:wrap;}
            .data_contrato {flex-basis: 49% !important;margin:0 1% 0 0!important;}
            .valor_contrato {flex-basis: 49% !important;margin:0 0 !important;}
            .valor_adesao {flex-basis: 49% !important;margin:0 1% 0 0 !important;}
            .data_boleto {flex-basis: 40% !important;margin:0 1% 0 0!important;}
            .vidas {flex-basis: 5% !important;margin:0 0 !important;}

            .line08 {flex-wrap: wrap;}
            .data_vigencia {flex-basis:25% !important;margin-right:1% !important;}
            .rede_plano {flex-basis:72% !important;margin:0 0% !important;}
            .segmentacao {flex-basis:100% !important;}

            .line09 {flex-wrap: wrap;}
            .plano {flex-basis:100% !important;}
            .tipo_plano_acomodacao {flex-basis:100% !important;}

            .parcela_parcela_um {
                margin-right:0px !important;
            }

            tr td {
                font-size:0.8em;
            }


        }




        #resultado {display:flex;flex-wrap: wrap;justify-content: space-between;}
        .ajax_load {display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;}
        .ajax_load_box{margin:auto;text-align:center;color:#fff;font-weight:var(700);text-shadow:1px 1px 1px rgba(0,0,0,.5)}
        .ajax_load_box_circle{border:16px solid #e3e3e3;border-top:16px solid #61DDBC;border-radius:50%;margin:auto;width:80px;height:80px;-webkit-animation:spin 1.2s linear infinite;-o-animation:spin 1.2s linear infinite;animation:spin 1.2s linear infinite}
        @-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
    </style>

@stop
