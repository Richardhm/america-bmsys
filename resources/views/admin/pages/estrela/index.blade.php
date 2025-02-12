@extends('adminlte::page')
@section('title', 'Orçamento')

@section('content_header')


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
    <section style="position:absolute;top:55px;left:0;background-color:#123449;width:100%;height:calc(100vh - 55px);">

        <div class="d-flex flex-column w-75 mx-auto">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column text-center text-white" style="font-size: 2em;line-height: 1em;">
                    <span class="font-italic">Ranking</span>
                    <span>de
                        <span style="color:#ffc107;">Vendas</span>
                    </span>
                </div>
                <div class="d-flex flex-column text-white text-center" style="line-height: 1.6em;">
                    <span style="font-size:2em;font-weight:bold;">{{$ano_atual}}</span>
                    <span>{{$semestre}}º Semestre</span>
                </div>
                <div>
                    <span class="text-white w-100 d-block font-italic text-center" style="font-size:1.2em;">Programa Estrela</span>
                    <div class="d-flex align-items-center px-3" style="border:1px solid #FFF;border-radius:5px;margin-bottom:10px;line-height: 1.1em;">
                        <i class="fas fa-medal mr-2" style="color:#ffc107;font-size:2em;text-align: center;"></i>
                        <span class="d-flex flex-column flex-wrap text-white">
                            <span style="color:#ffc107;margin-bottom:0;padding-bottom:0;">Melhores</span>
                            <span style="display:flex;">VENDEDORES</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="content_table w-100">
                <table class="table w-100 table-borderless">
                    <thead>
                        <tr>
                            <th style="width:3%"></th>
                            <th style="width:20%;">VENDEDORES</th>
                            <th style="width:5.6%">
                                JUL<br />25 Vidas
                            </th>
                            <th style="width:5.6%">
                                AGO<br />25 Vidas
                            </th>
                            <th style="width:5.6%">
                                SET<br />25 Vidas
                            </th>
                            <th style="width:5.6%">
                                OUT<br />25 Vidas
                            </th>
                            <th style="width:5.6%">
                                NOV<br />25 Vidas
                            </th>
                            <th style="width:5.6%">
                                DEZ<br />25 Vidas
                            </th>
                            <th style="width:5.6%">
                                TOTAL<br />150 Vidas
                            </th>
                            <th style="width:4%">%</th>
                            <th style="width:5%">FALTAM</th>
                            <th style="width:20%">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ranking as $r)
                        @if($r->quantidade != 0)
                        <tr>
                            <td style="width:3%">{{$loop->iteration}}°</td>
                            <td style="width:20%;">
                                <div style="background-color:white;border-radius:8px;width:96%;color:black;padding:3px;text-align:left;">
                                    {{$r->usuario}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->julho}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->agosto}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->setembro}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->outubro}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->novembro}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->dezembro}}
                                </div>
                            </td>
                            <td style="width:5.6%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->quantidade}}
                                </div>
                            </td>
                            <td style="width:4%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:6px;font-size:0.7em;">
                                    @if($r->status == "nao_classificado")
                                        {{number_format(($r->quantidade / 150) * 100,2)}}
                                    @elseif($r->status == "tres_estrelas")
                                        {{number_format((($r->quantidade - 150) / (190 - 150)) * 100, 2)}}
                                    @elseif($r->status == "quatro_estrelas")
                                        {{number_format((($r->quantidade - 191) / (250 - 191)) * 100, 2)}}
                                    @else
                                        100
                                    @endif
                                </div>
                            </td>
                            <td style="width:4%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    {{$r->falta}}
                                </div>
                            </td>
                            <td style="width:20%">
                                <div style="background-color:white;border-radius:8px;width:90%;color:black;padding:3px;">
                                    @if($r->status == "nao_classificado")
                                        Não Classificado
                                    @elseif($r->status == "tres_estrelas")
                                        <div class="d-flex">
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                        </div>

                                    @elseif($r->status == "quatro_estrelas")
                                        <div class="d-flex">
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                        </div>
                                    @else
                                        <div class="d-flex">
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                            <i class="fas fa-star fa-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex w-100 py-2">
                <div class="d-flex align-items-center justify-content-between justify-content-end" style="width:50%;">

                    <div class="d-flex flex-column text-white text-center" style="line-height:1;font-size:0.785em;">
                        <span>3 Estrelas</span>
                        <span>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                        </span>
                        <span>
                            150 a 190 vidas
                        </span>
                    </div>

                    <div class="d-flex flex-column text-white text-center" style="line-height:1;font-size:0.785em;">
                        <span>4 Estrelas</span>
                        <span>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                        </span>
                        <span>191 a 250 vidas</span>
                    </div>

                    <div class="d-flex flex-column text-white text-center" style="line-height:1;font-size:0.785em;">
                        <span>5 Estrelas</span>
                        <span>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                            <i class="fas fa-star fa-xs"></i>
                        </span>
                        <span>Apartir de 251 vidas</span>
                    </div>


                </div>


                <div class="d-flex align-items-center justify-content-end" style="width:50%;line-height:1;font-size:0.785em;">
                    <p class="text-white d-flex flex-column text-center mr-5 align-items-center my-auto">
                        <span>Confirmação</span>
                        <span>80% 2º parcela</span>
                    </p>
                    <div>
                        <div>
                            <img src="{{asset('storage/logo-hapvida-NotreDame-Intermedica.png')}}" alt="Hapvida" style="width:200px;background-color:white;padding:10px;border-radius:5px;">
                        </div>
                    </div>
                </div>

            </div>
        </div>




    </section>
@stop

@section('css')
    <style>
        table {
            border-collapse: separate;
        }
        .table th, .table td {
            padding:0 !important;
        }


        table tr th {
            font-size:0.7em;
            text-align: center;
            color:#FFF;
        }

        table tbody tr td {
            font-size:0.85em;
            text-align: center;
            color:#FFF;
        }




        .content_table {
            background-color: #ffc107;
            border-radius:10px;height:530px;min-height: 520px;overflow:auto;




        }

        /* Estilo da barra de rolagem */
        .content_table::-webkit-scrollbar {
            width: 10px; /* Largura da barra de rolagem */
        }

        /* Estilo da "trilha" da barra de rolagem */
        .content_table::-webkit-scrollbar-track {
            background: #f1f1f1; /* Cor de fundo da trilha */
            border-radius: 5px; /* Raio das bordas da trilha */
        }

        /* Estilo do "polegar" da barra de rolagem */
        .content_table::-webkit-scrollbar-thumb {
            background: #0c525d; /* Cor do polegar da barra de rolagem */
            border-radius: 5px; /* Raio das bordas do polegar */
        }

        /* Estilo do "polegar" da barra de rolagem quando o mouse passa por cima */
        .content_table::-webkit-scrollbar-thumb:hover {
            background: #555; /* Cor do polegar da barra de rolagem ao passar o mouse */
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

        });

    </script>
@stop




