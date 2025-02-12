@extends('adminlte::page')
@section('title', 'Orçamento')
@section('plugins.Toastr', true)
@section('content_header')
    <h3 class="text-white">Orçamento</h3>
    <div id="desfazer">

    </div>
@stop

@section('content_top_nav_right')
    <li class="rounded border border-white">
        <a class="text-white nav-link" href="{{route('orcamento.index')}}">Orçamento</a>
    </li>
    <li class="bg-white rounded">
        <a class="text-white nav-link" href="{{route('orcamento.search.home')}}">Tabelas</a>
    </li>
    <li class="bg-white rounded">
        <a class="text-white nav-link" href="{{route('home.administrador.consultar')}}">Consultar</a>
    </li>

@stop

@section('content')
    <div class="ajax_load">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>
    <div id="error_pdf"></div>
    <div class="card shadow" style="background-color:#123449;color:#FFF;" id="container_formulario">
        <div class="card-body" style="box-shadow: rgba(0,0,0,0.8) 0.6em 0.7em 5px;">
            <form action="" method="post" class="px-3">
                @csrf
                <div class="form-group">
                    <p>Tabela Origem:</p>
                    <select name="origem_cidade" id="origem_cidade" class="form-control">
                        <option value="">--Escolher a cidade--</option>
                        @foreach($cidades as $c)
                            <option value="{{$c->id}}">{{$c->nome}}</option>
                        @endforeach
                    </select>
                    <div class="error_origem_cidade"></div>
                </div>
                <div class="errorFaixa"></div>
                <div class="container_contador_numerico">
                    <div class="contador_numerico contador_numerico_um">
                        <span class="text-white">0-18</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-0-18" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" data-change="change_faixa_0_18" name="faixas_etarias[1]" value="{{isset($colunas) && in_array(1,$colunas) ? $faixas[array_search(1, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-0-18" class="text-center font-weight-bold flex-fill faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" class="text-center" />
                                <button type="button" class="d-flex align-items-center justify-content-center plus" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="contador_numerico contador_numerico_dois">
                        <span class="text-white">19-23</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-19-23" style="border:none;background:#FF0000;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em">－</span>
                                </button>
                                <input type="tel" data-change="change_faixa_19_23" name="faixas_etarias[2]" value="{{isset($colunas) && in_array(2,$colunas) ? $faixas[array_search(2, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-19-23" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" class="text-center" />
                                <button type="button" class="d-flex align-items-center justify-content-center plus" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="contador_numerico contador_numerico_tres">
                        <span class="text-white">24-28</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="d-flex align-items-center justify-content-center minus bg-danger" id="faixa-24-28" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em">－</span>
                                </button>
                                <input type="tel" data-change="change_faixa_24_28" name="faixas_etarias[3]" value="{{isset($colunas) && in_array(3,$colunas) ? $faixas[array_search(3, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-24-28" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" class="text-center" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="contador_numerico contador_numerico_quatro margin_de_lado_um">
                        <span class="text-white">29-33</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-29-33" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" data-change="change_faixa_29_33" name="faixas_etarias[4]" value="{{isset($colunas) && in_array(4,$colunas) ? $faixas[array_search(4, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-29-33" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" class="text-center" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div  class="contador_numerico contador_numerico_cinco">
                        <span class="text-white">34-38</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-34-38" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" name="faixas_etarias[5]" data-change="change_faixa_34_38" value="{{isset($colunas) && in_array(5,$colunas) ? $faixas[array_search(5, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-34-38" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="contador_numerico contador_numerico_seis margin_de_lado_um">
                        <span class="text-white">39-43</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-39-43" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" name="faixas_etarias[6]" data-change="change_faixa_39_43" value="{{isset($colunas) && in_array(6,$colunas) ? $faixas[array_search(6, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-39-43" class="text-center font-weight-bold flex-fill w-25 faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" class="text-center" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div  class="contador_numerico contador_numerico_sete">
                        <span class="text-white">44-48</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-44-48" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" name="faixas_etarias[7]" data-change="change_faixa_44_48" value="{{isset($colunas) && in_array(7,$colunas) ? $faixas[array_search(7, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-44-48" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="contador_numerico contador_numerico_oito margin_de_lado_um">
                        <span class="text-white">49-53</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-49-53" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" name="faixas_etarias[8]" data-change="change_faixa_49_53" value="{{isset($colunas) && in_array(8,$colunas) ? $faixas[array_search(8, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-49-53" class="text-center font-weight-bold faixas_etarias" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="contador_numerico contador_numerico_nove">
                        <span class="text-white">54-58</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger" id="faixa-54-58" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" name="faixas_etarias[9]" data-change="change_faixa_54_58" value="{{isset($colunas) && in_array(9,$colunas) ? $faixas[array_search(9, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-54-58"  class="text-center font-weight-bold faixas_etarias d-flex" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" />
                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="contador_numerico contador_numerico_dez">
                        <span class="text-white">59+</span>
                        <div class="border border-white rounded">
                            <div class="d-flex content">
                                <button type="button" class="minus align-items-center d-flex justify-content-center bg-danger"  id="faixa-59" style="border:none;width:30%;max-height:30px;" aria-label="−" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">－</span>
                                </button>
                                <input type="tel" data-change="change_faixa_59" name="faixas_etarias[10]" value="{{isset($colunas) && in_array(10,$colunas) ? $faixas[array_search(10, array_column($faixas, 'faixa_etaria_id'))]['faixa_quantidade'] : ''}}" id="faixa-input-59" class="text-center font-weight-bold faixas_etarias d-flex" style="border:none;width:40%;font-size:1.2em;" value="" step="1" min="0" />

                                <button type="button" class="plus align-items-center d-flex justify-content-center" style="border:none;background-color:rgb(17,117,185);width:30%;max-height:30px;" aria-label="+" tabindex="0">
                                    <span class="text-white font-weight-bold" style="font-size:1.5em;">＋</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-block btn-light my-3" value="Ver Planos" name="verPlanos" />
            </form>
        </div>
    </div>


    <div id="aquiadministradoras"></div>

    <div id="aquiPlano"></div>


@stop

@section('css')
    <style>
        .ajax_load {display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;}
        .ajax_load_box{margin:auto;text-align:center;color:#fff;font-weight:var(700);text-shadow:1px 1px 1px rgba(0,0,0,.5)}
        .ajax_load_box_circle{border:16px solid #e3e3e3;border-top:16px solid #61DDBC;border-radius:50%;margin:auto;width:80px;height:80px;-webkit-animation:spin 1.2s linear infinite;-o-animation:spin 1.2s linear infinite;animation:spin 1.2s linear infinite}
        @-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
        .container_contador_numerico {display:flex;}
        .menu_personalizado {display:flex;align-items: center;width:200px;justify-content: space-between;}
        .container_contador_numerico .contador_numerico {flex-basis:10%;}
        .contador_numerico.contador_numerico_nove {margin:0 10px 0 0;}
        .contador_numerico.contador_numerico_dois {margin:0 10px;}
        .contador_numerico.contador_numerico_quatro {margin:0 10px;}
        .contador_numerico.contador_numerico_seis {margin:0 10px;}
        .contador_numerico.contador_numerico_oito{margin:0 10px;}
        #aquiadministradoras {display: flex;justify-content: space-between;}
        .administradoras_container {width:22%;align-items: center;border-radius: 5px;padding:5px 0;color:#FFF;}
        .administradoras_container img {background-color: #FFF;padding:10px;border-radius:5px;}
        .administradoras_container:hover img {cursor:pointer;border:2px solid black;}

        .fullwidth {width:100% !important;}
        .cards_destaque_links {text-align:center;display:flex;align-items: center;justify-content: center;min-height:50px;display:flex;}
        .cards {cursor: pointer;}
        div p {margin-bottom:0px !important;}
        * {margin:0;padding:0;box-sizing:border-box;}
        .container_planos_section {display:flex;flex-wrap:wrap;justify-content: space-between;}
        .planos {margin-bottom:15px;border:2px solid black;border-radius: 10px;display:flex;flex-wrap:wrap;flex-basis: 49%;box-shadow: 5px 5px 5px 5px black;}




        .logo {display:flex;flex-basis:100%;justify-content: center;}
        .coparticipacao_odonto {display:flex;margin:0 auto;flex-basis:90%;font-size:1em;margin-right:10px;align-items: center;justify-content: center;}
        .coparticipacao_odonto p {font-weight:bold;font-size:1.1em;text-decoration: underline;}
        .faixas_etarias_container {margin-left:8px;}
        .faixas_etarias_title {background-color:rgb(49,134,155);padding:16px 0;color:#FFF;border-right: 1px solid black;}
        .faixas_etarias_nome {border:1px solid black;padding:5px;box-sizing:border-box;}
        .faixas_total_plano {background-color:rgb(49,134,155);color:#FFF;padding:10px 5px;border-right:1px solid black;}
        div.apartamento,div.enfermaria,div.ambulatorial {display: flex;flex-direction: column;flex-basis: 25%;}
        div.apartamento .plano_container_header,div.enfermaria .plano_container_header {border-right:1px solid black;}
        .plano_container_header_acomodacao {display: block;text-align: center;color:#FFF;}
        .plano_container_header {background-color:rgb(49,134,155);padding:5px;}
        .plano_total {border:1px solid black;padding:5px;box-sizing:border-box;text-align: center;}
        .total_somado {background-color:rgb(49,134,155);font-weight: bold;text-align: center;color:#FFF;padding:10px 5px;}
        div.apartamento .total_somado,div.enfermaria .total_somado {border-right:1px solid black;}
        .plano_container_header_title {font-size: 0.9em;text-align: center;display: block;color:#FFF;}
        .planos:hover {box-shadow: inset 0 0 1em black, 0 0 1em #808080;cursor:pointer;}
        .imagem-operadora a {margin-left:10px;}
        .imagem-operadora a:hover img {box-shadow: 5px 5px 5px 5px black;padding:10px;}
        .card_plano {flex-basis:31.5%;margin:0 1.8% 2% 0;background-color:#123449;color:#FFF;}
        .card_plano:hover {cursor: pointer;}
        .card_plano table {border: 1px solid #FFF;width: 100%;border-collapse: collapse;}

        .card_card {
            box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;padding:0.6rem;
        }

        .card_plano_ambulatorial {flex-basis:31.5%;margin:0 1.8% 2% 0;background-color:#123449;color:#FFF;}
        .card_plano_ambulatorial:hover {cursor: pointer;}
        .card_plano_ambulatorial table {border: 1px solid #FFF;width: 90%;border-collapse: collapse;margin: 0 auto;}
        .faixa_etaria_estilo {
            background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid #FFF;border-right:1px solid #FFF;
        }
        .valores_com_coparticipacao_estilo {
            text-align:right;font-size:0.875em;border-right:1px solid #FFF;
        }
        .valores_sem_coparticipacao_estilo {
            text-align:right;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;
        }

        .borda_direita {
            border-right:1px solid white
        }


        @media (max-width: 800px) {
            .menu_personalizado {justify-content: space-between;}
            .toda_janela {display: none;}
            .container_contador_numerico {flex-wrap: wrap;justify-content: space-between;}
            .contador_numerico.contador_numerico_quatro {margin:0 !important;}
            .contador_numerico.contador_numerico_seis {margin:0 !important;}
            .contador_numerico.contador_numerico_nove {margin:0 !important;}
            .container_contador_numerico .contador_numerico {flex-basis:30%;}
            .card_plano {flex-basis: 100% !important;}

            .administradoras_container {width:100%;}
            #aquiadministradoras {flex-wrap: wrap;}
            .administradoras_container {width:48% !important;margin-bottom:5px;}
            .planos {box-shadow: none !important;}
            .card_card {
                box-shadow:none !important;
            }

            .card {
                flex-basis:100%;
                /*display: none;*/
            }
            .card table {
                width: 100% !important;
            }



            table tbody td {
                font-size:0.74em !important;

            }
            table thead td {
                font-size:0.74em !important;
            }

            table tfoot td {
                font-size:0.74em !important;
            }

            /*.card_plano_ambulatorial {*/
            /*    display:flex;*/
            /*    flex-wrap: wrap;*/
            /*}*/

            /*.card_plano_ambulatorial table {*/
            /*    flex-basis: 100% !important;*/
            /*}*/




        }




    </style>
@stop





@section('js')
    <script>
        $(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            let plus = $(".plus");
            let minus = $(".minus");
            $(plus).on('click',function(e){
                let alvo = e.target;
                let pai = alvo.closest('.content');
                let input = $(pai).find('input');
                if(input.val() == "") {
                    input.val(0);
                }
                let newValue = parseInt(input.val()) + 1;
                if(newValue >= 0) {
                    input.val(newValue);
                }
            });

            $(minus).on('click',function(e){
                let alvo = e.target;
                let pai = alvo.closest('.content');
                let input = $(pai).find('input');
                let newValue = parseInt(input.val()) - 1;
                if(newValue >= 0) {
                    input.val(newValue);
                }
            });

            $("body").on('click','.administradoras_container',function(){
                let id = $(this).attr("id");
                let tabela_origem = $("#origem_cidade").val();

                $.ajax({
                    url:"{{route('orcamento.montarOrcamento')}}",
                    method:"POST",
                    data:{
                        "tabela_origem": tabela_origem,
                        "administradora":id,
                        "faixas" : [{
                            '1' : $('#faixa-input-0-18').val(),
                            '2' : $('#faixa-input-19-23').val(),
                            '3' : $('#faixa-input-24-28').val(),
                            '4' : $('#faixa-input-29-33').val(),
                            '5' : $('#faixa-input-34-38').val(),
                            '6' : $('#faixa-input-39-43').val(),
                            '7' : $('#faixa-input-44-48').val(),
                            '8' : $('#faixa-input-49-53').val(),
                            '9' : $('#faixa-input-54-58').val(),
                            '10' : $('#faixa-input-59').val()
                        }]
                    },
                    success:function(res) {
                        // console.log(res);
                        if(res != "error_pdf") {
                            $("#container_formulario").slideUp(1000,function(){
                                $("#aquiPlano").css({"margin-top":"10px","display":"none"}).html(res).slideDown(1000,function(){
                                    $(this).css({"display":"block"});
                                    $("#desfazer").html("<i class='fas fa-undo'></i>")
                                })
                            });
                        } else {
                            $("#container_formulario").slideUp(1000,function(){
                                $("#aquiPlano").css({"margin-top":"10px","display":"none"}).html("<p class='alert alert-danger text-center'>Para criar o pdf so aceitamos até 6 faixa etárias</p>").slideDown(1000,function(){
                                    $(this).css({"display":"block"});
                                    $("#desfazer").html("<i class='fas fa-undo'></i>")
                                })
                            });
                        }



                    }
                });



            });


            $("body").on('click',"#desfazer",function(){
                $("#aquiPlano").slideUp(1000,function(){
                    $(this).html('');
                    $("#container_formulario").slideDown(1000,function(){
                        $("#aquiadministradoras").slideUp(1000);
                        $("#desfazer").html("");
                    });
                });
            });





            $('body').on('click','input[name="verPlanos"]',function(e){

                if($("#origem_cidade").val() == "") {
                    $(".error_origem_cidade").html("<p class='alert alert-danger text-center'>Escolher Cidade Origem</p>")
                    return false;
                } else {
                    $(".error_origem_cidade").html("");

                }

                if($("#faixa-input-0-18").val() == "" && $('#faixa-input-19-23').val() == "" && $('#faixa-input-24-28').val() == "" &&  $('#faixa-input-29-33').val() == "" && $('#faixa-input-34-38').val() == "" && $('#faixa-input-39-43').val() == "" && $('#faixa-input-44-48').val() == "" && $('#faixa-input-49-53').val() == "" && $('#faixa-input-54-58').val() == "" && $('#faixa-input-59').val() == "") {
                    $(".errorFaixa").html("<p class='alert alert-danger text-center'>Alguma faixa etaria deve ter preenchida</p>");
                    return false;
                } else {
                    $(".errorFaixa").html('');
                }

                $.ajax({
                    url:"{{route('orcamento.administradoras.montar')}}",
                    method:"POST",
                    success:function(res) {

                        $("#aquiadministradoras").css({"display":"none"}).slideDown(1000,function(){
                            $(this).html(res);
                        });

                    }
                });

                return false;
            });

            $("body").on('click','.card_plano:not(".cards_destaque_links")',function(){

                $(".cards_destaque_links").remove();
                let administradora_id = $(this).find('input[name="administradora_id"]').val();
                let plano_id = $(this).find('input[name="plano_id"]').val();

                var element = $('<div></div>');
                var links = `
                    <a style="color:#FFF;margin-left:10px;display:flex;flex-basis:100%;align-items:center;justify-content: center;" class="border p-1 border-dark rounded enviar_mensagem bg-danger pdf" href="">
                             <span style="margin-right:15px;">Criar Imagem</span>
                            <i class="fas fa-file-image"></i>
                        </a>
                `
                element.html(links);
                element.addClass("cards_destaque_links")
                element.hide();

                $(this).find('table').after(element);
                element.fadeIn();





                // if(plano_id != 5) {

                // 	var element = $('<div></div>');
                //             	var links = `
                //                     <a style="color:#FFF;margin-left:10px;display:flex;flex-basis:100%;align-items:center;justify-content: center;" class="border p-1 border-dark rounded enviar_mensagem bg-danger pdf" href="">
                //                         <span style="margin-right:15px;">Criar PDF</span>
                //                         <i class="fas fa-file-pdf"></i>
                //                     </a>
                //                 `
                //             	element.html(links);
                //             	element.addClass("cards_destaque_links")
                //             	element.hide();

                //             	$(this).find('table').after(element);
                //             	element.fadeIn();


                // } else {


                // 	var element = $('<div></div>');
                //             	var links = `
                //                     <a style="color:#FFF;margin-left:10px;display:flex;flex-basis:100%;align-items:center;justify-content: center;" class="border p-1 border-dark rounded enviar_mensagem bg-danger pdf_empresarial" href="">
                //                         <span style="margin-right:15px;">Criar PDF</span>
                //                         <i class="fas fa-file-pdf"></i>
                //                     </a>
                //                 `
                //             	element.html(links);
                //             	element.addClass("cards_destaque_links")
                //             	element.hide();

                //             	$(this).find('table').after(element);
                //             	element.fadeIn();






                // }






            });

            $("body").on('click','.card_plano_ambulatorial:not(".cards_destaque_links")',function(){



                $(".cards_destaque_links").remove();
                let administradora_id = $(this).find('input[name="administradora_id"]').val();




                var element = $('<div></div>');
                var links = `
                        <a style="color:#FFF;margin-left:10px;display:flex;flex-basis:100%;align-items:center;justify-content: center;" class="border p-1 border-dark rounded enviar_mensagem bg-danger pdf_ambulatorial" href="">
                             <span style="margin-right:15px;">Criar Imagem</span>
                            <i class="fas fa-file-image"></i>
                        </a>
                    `
                element.html(links);
                element.addClass("cards_destaque_links")
                element.hide();


                $(this).find('table').after(element);
                element.fadeIn();


            });

            /*
            $("body").on('click','.pdf',function(){
                let tabela_origem 	  = $("#origem_cidade").val();
                let administradora_id = $(this).closest('.card_plano').find('#administradora_id').val();
                let plano_id = $(this).closest('.card_plano').find('#plano_id').val();
                let odonto = $(this).closest('.card_plano').find("#plano_com_sem_odonto").text();

                $.ajax({
                    url:"{{route('orcamento.criarpdf')}}",
                    method:"POST",
                    data:{
                    	"tabela_origem": tabela_origem,
						"administradora_id":administradora_id,
						"plano_id":plano_id,
						"odonto":odonto,
                    	"faixas" : [{
                            '1' : $('#faixa-input-0-18').val(),
                            '2' : $('#faixa-input-19-23').val(),
                            '3' : $('#faixa-input-24-28').val(),
                            '4' : $('#faixa-input-29-33').val(),
                            '5' : $('#faixa-input-34-38').val(),
                            '6' : $('#faixa-input-39-43').val(),
                            '7' : $('#faixa-input-44-48').val(),
                            '8' : $('#faixa-input-49-53').val(),
                            '9' : $('#faixa-input-54-58').val(),
                            '10' : $('#faixa-input-59').val()
                        }]
                    },
					success:function(res) {
					 	console.log(res);
					}
				});

				return false;

			});
			*/


            $("body").on('click','.pdf_ambulatorial',function(){
                var load = $(".ajax_load");
                let tabela_origem 	  = $("#origem_cidade").val();
                let administradora_id = $(this).closest('.card_plano_ambulatorial').find('#administradora_id').val();
                let plano_id = $(this).closest('.card_plano_ambulatorial').find('#plano_id').val();
                let odonto = $(this).closest('.card_plano_ambulatorial').find("#plano_com_sem_odonto").text();
                $.ajax({
                    url:"{{route('orcamento.criarpdfambulatorial')}}",
                    method:"POST",
                    data:{
                        "tabela_origem": tabela_origem,
                        "administradora_id":administradora_id,
                        "plano_id":plano_id,
                        "odonto":odonto,
                        "faixas" : [{
                            '1' : $('#faixa-input-0-18').val(),
                            '2' : $('#faixa-input-19-23').val(),
                            '3' : $('#faixa-input-24-28').val(),
                            '4' : $('#faixa-input-29-33').val(),
                            '5' : $('#faixa-input-34-38').val(),
                            '6' : $('#faixa-input-39-43').val(),
                            '7' : $('#faixa-input-44-48').val(),
                            '8' : $('#faixa-input-49-53').val(),
                            '9' : $('#faixa-input-54-58').val(),
                            '10' : $('#faixa-input-59').val()
                        }]
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },

                    beforeSend: function () {
                        load.fadeIn(100).css("display", "flex");
                    },



                    success:function(blob,status,xhr,ppp) {
                        load.fadeOut(300);

                        if(blob.size && blob.size != undefined) {

                            var filename = "";
                            var disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                var matches = filenameRegex.exec(disposition);
                                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                            }
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);
                                if (filename) {
                                    var a = document.createElement("a");
                                    if (typeof a.download === 'undefined') {
                                        window.location.href = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        a.download = filename;
                                        document.body.appendChild(a);
                                        a.click();
                                    }
                                } else {
                                    window.location.href = downloadUrl;
                                }
                                setTimeout(function () {
                                    URL.revokeObjectURL(downloadUrl);
                                },100);
                            }




                        } else {
                            toastr["error"]("PDF Não suporta essa quantidade de linhas")
                            toastr.options = {
                                'time-out': 3000,
                                'close-button':true,
                                'position-class':'toast-top-full-width',
                                'class' : 'fullwidth',
                                'fixed': false

                            }
                            return false;
                        }



                    }

                });
                return false;
            });




            $("body").on('click','.pdf',function(){
                var load = $(".ajax_load");
                let tabela_origem 	  = $("#origem_cidade").val();
                let administradora_id = $(this).closest('.card_plano').find('#administradora_id').val();
                let plano_id = $(this).closest('.card_plano').find('#plano_id').val();
                let odonto = $(this).closest('.card_plano').find("#plano_com_sem_odonto").text();
                $.ajax({
                    url:"{{route('orcamento.criarpdf')}}",
                    method:"POST",
                    data:{
                        "tabela_origem": tabela_origem,
                        "administradora_id":administradora_id,
                        "plano_id":plano_id,
                        "odonto":odonto,
                        "faixas" : [{
                            '1' : $('#faixa-input-0-18').val(),
                            '2' : $('#faixa-input-19-23').val(),
                            '3' : $('#faixa-input-24-28').val(),
                            '4' : $('#faixa-input-29-33').val(),
                            '5' : $('#faixa-input-34-38').val(),
                            '6' : $('#faixa-input-39-43').val(),
                            '7' : $('#faixa-input-44-48').val(),
                            '8' : $('#faixa-input-49-53').val(),
                            '9' : $('#faixa-input-54-58').val(),
                            '10' : $('#faixa-input-59').val()
                        }]
                    },

                    // success:function(res) {
                    // 	console.log(res);
                    // }

                    xhrFields: {
                        responseType: 'blob'
                    },

                    beforeSend: function () {
                        load.fadeIn(100).css("display", "flex");
                    },

                    success:function(blob,status,xhr,ppp) {
                        load.fadeOut(300);

                        if(blob.size && blob.size != undefined) {

                            var filename = "";
                            var disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                var matches = filenameRegex.exec(disposition);
                                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                            }
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);
                                if (filename) {
                                    var a = document.createElement("a");
                                    if (typeof a.download === 'undefined') {
                                        window.location.href = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        a.download = filename;
                                        document.body.appendChild(a);
                                        a.click();
                                    }
                                } else {
                                    window.location.href = downloadUrl;
                                }
                                setTimeout(function () {
                                    URL.revokeObjectURL(downloadUrl);
                                },100);
                            }




                        } else {
                            toastr["error"]("PDF Não suporta essa quantidade de linhas")
                            toastr.options = {
                                'time-out': 3000,
                                'close-button':true,
                                'position-class':'toast-top-full-width',
                                'class' : 'fullwidth',
                                'fixed': false

                            }
                            return false;
                        }



                    }

                });
                return false;
            });


            $("body").on('click','.pdf_empresarial',function(){
                let tabela_origem 	  = $("#origem_cidade").val();
                let administradora_id = $(this).closest('.card_plano').find('#administradora_id').val();
                let plano_id = $(this).closest('.card_plano').find('#plano_id').val();
                let odonto = $(this).closest('.card_plano').find("#plano_com_sem_odonto").text();
                $.ajax({
                    url:"{{route('orcamento.criarpdfempresarial')}}",
                    method:"POST",
                    data:{
                        "tabela_origem": tabela_origem,
                        "administradora_id":administradora_id,
                        "plano_id":plano_id,
                        "odonto":odonto,
                        "faixas" : [{
                            '1' : $('#faixa-input-0-18').val(),
                            '2' : $('#faixa-input-19-23').val(),
                            '3' : $('#faixa-input-24-28').val(),
                            '4' : $('#faixa-input-29-33').val(),
                            '5' : $('#faixa-input-34-38').val(),
                            '6' : $('#faixa-input-39-43').val(),
                            '7' : $('#faixa-input-44-48').val(),
                            '8' : $('#faixa-input-49-53').val(),
                            '9' : $('#faixa-input-54-58').val(),
                            '10' : $('#faixa-input-59').val()
                        }]
                    },


                    xhrFields: {
                        responseType: 'blob'
                    },
                    success:function(blob,status,xhr,ppp) {




                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);
                            if (filename) {
                                var a = document.createElement("a");
                                if (typeof a.download === 'undefined') {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }
                            setTimeout(function () {
                                URL.revokeObjectURL(downloadUrl);
                            },100);
                        }
                    }




                });
                return false;
            });




        });

    </script>
@stop




