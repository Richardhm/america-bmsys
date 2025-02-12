@extends('adminlte::page')
@section('title', 'Dashboard')
@section('plugins.Chartjs', true)
@section('plugins.ChartGoogle', true)

@section('content_top_nav_right')
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content_header')
    <!-- <div style="display:flex;">
        <p class="text-white link-down">Dashboard</p>
        <p class="text-white grafico link-down mx-2">Grafico</p>
        <p class="text-white anual link-down">Anual</p>
    </div>     -->
@stop

@section('content')
    <div class="row">
        <!-- Coluna 1 -->
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Período</span>
                    <span class="info-box-number">Outubro (Mês Atual)</span>

                </div>

                <div class="info-box-content">
                    <span class="info-box-text text-center">Total de Vidas</span>
                    <span class="info-box-number text-center">200</span>
                </div>

            </div>
        </div>

        <!-- Coluna 2 -->
        <div class="col-md-4">
            <div class="info-box bg-primary">
                <div class="info-box-content">
                    <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="info-box-text">Dashboard</span>
                </div>


                <div class="info-box-content">

                    <div class="form-group">

                        <select class="form-control" id="mes">
                            <option value="01">Janeiro</option>
                            <option value="02">Fevereiro</option>
                            <option value="01">Março</option>
                            <option value="02">Abril</option>
                            <option value="01">Maio</option>
                            <option value="02">Junho</option>
                            <option value="01">Julho</option>
                            <option value="02">Agosto</option>
                            <option value="01">Setembro</option>
                            <option value="02">Outubro</option>
                            <option value="02">Novembro</option>
                            <option value="02">Dezembro</option>
                            <!-- Adicione os outros meses aqui -->
                        </select>
                    </div>

                </div>

                <div class="info-box-content">
                    <div class="form-group">

                        <select class="form-control" id="ano">
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <!-- Adicione os outros anos aqui -->
                        </select>
                    </div>
                </div>



            </div>
        </div>

        <!-- Coluna 3 -->
        <div class="col-md-4">
            <div class="info-box bg-success">

                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Período</span>
                    <span class="info-box-number">2023</span>
                </div>

                <div class="info-box-content">
                    <span class="info-box-text">Valor Total</span>
                    <span class="info-box-number">R$ 55.056</span>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Small Box 1 -->
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Qualicorp</h3>
                    <p>Vidas: 10 Valor: R$ 1000</p>
                </div>
                <div class="icon">
                    <i class="fas fa-heartbeat fa-lg"></i>
                </div>
            </div>
        </div>

        <!-- Small Box 2 -->
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Allcare</h3>
                    <p>Vidas: 10 Valor: R$ 1000</p>
                </div>
                <div class="icon">
                    <i class="fas fa-heartbeat fa-lg"></i>
                </div>
            </div>
        </div>

        <!-- Small Box 3 -->
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Alter</h3>
                    <p>Vidas: 10 Valor: R$ 1000</p>
                </div>
                <div class="icon">
                    <i class="fas fa-heartbeat fa-lg"></i>
                </div>
            </div>
        </div>

        <!-- Small Box 4 -->
        <div class="col-md-3">
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>Hapvida</h3>
                    <p>Vidas: 10 Valor: R$ 1000</p>
                </div>
                <div class="icon">
                    <i class="fas fa-heartbeat fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <section class="row">
        <!-- Primeira Coluna - Podios -->
        <div class="col-md-5 border border-width-3 border-info rounded" style="height:400px;background-color:rgba(205, 232, 215,0.5);">

            <div class="d-flex w-75 mx-auto">

                <div class="d-flex" style="width:150px;align-items: flex-end;align-content: flex-end;height:400px;">
                    <div class="d-flex flex-column" style="width:150px;">
                        <p style="display:flex;justify-content: center;margin:0;padding:0;">
                            <img src="{{asset('storage/users/HKRnCOW5QTP4YfC7Hha6IfHho4mWCKcqtnx2nUbh.png')}}" alt="Default" width="110px" style="border-radius:50%;border:3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                        </p>
                        <p class="text-center" style="margin:0;padding:0;">R$ 1,77</p>
                        <div style="width:100%;height:180px;background-color:white;display:flex;align-items:flex-end;justify-content: center;border-radius:5px 5px 0 0;">
                            <i class="fas fa-medal fa-lg" style="color:#000;font-size:4em;margin-bottom: 10px;"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex" style="width:150px;align-items: flex-end;align-content: flex-end;height:400px">
                    <div class="d-flex flex-column" style="width:150px;">
                        <p style="display:flex;justify-content: center;margin:0;padding:0;">
                            <img src="{{asset('storage/users/1696421827_4iGySSimaR7PUlgwMK9UHCQ7CgEmPiTui29D8CCj.png')}}" alt="Default" width="110px" style="border-radius:50%;border:3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                        </p>
                        <p class="text-center" style="margin:0;padding:0;">R$ 1,77</p>
                        <div style="width:100%;height:250px;background-color:#d8a200;display:flex;align-items:flex-end;justify-content: center;border-radius:5px 5px 0 0">
                            <i class="fas fa-trophy fa-lg" style="color:#FFF;font-size:6em;margin-bottom: 10px;"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex" style="width:150px;align-items: flex-end;align-content: flex-end;height:400px;">
                    <div class="d-flex flex-column" style="width:150px;">
                        <p style="display:flex;justify-content: center;margin:0;padding:0;">
                            <img src="{{asset('storage/users/sX7rbu4AoO6Wbs7rxAyAZuz2CaFkTrVVT7cJKUdP.jpg')}}" alt="Default" width="110px" style="border-radius:50%;border:3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                        </p>
                        <p class="text-center" style="margin:0;padding:0;">R$ 1,77</p>
                        <div style="width:100%;height:120px;background-color:white;display:flex;align-items:flex-end;justify-content: center;border-radius:5px 5px 0 0;">
                            <i class="fas fa-medal fa-lg" style="color:#000;font-size:4em;margin-bottom: 10px;"></i>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <div class="col-md-7" id="ranking_user">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Foto</th>
                        
                        <th>Vidas</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $ii=0;
                    @endphp
                    @foreach($ranking as $u)
                        <tr>
                            <td>
                                @php  echo ++$ii; @endphp
                            </td>
                            <td>{{$u->usuario}}</td>
                            <td><img src="{{$u->imagem ? asset('storage/'.$u->imagem) : asset('storage/avatar-default.jpg')}}" alt="{{$u->usuario}}" width="50px" height="50px"></td>
                            
                            <td>{{$u->quantidade}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="row mt-2">
        <div class="col-md-5" style="height:200px;background-color:#123449;border-radius:5px;">
                <table class="table table-sm table-borderless w-100" style="color:white;margin-bottom:0rem;border-radius:5px;">
                    <thead>
                    <tr class="text-center border-bottom">
                        <th colspan="5">Outubro / 2023</th>
                    </tr>
                    <tr class="border-bottom">
                        <th>Plano</th>
                        <th>Meta</th>
                        <!-- <th>Meta</th> -->
                        <th>Cad.</th>
                        <th>Real.</th>
                        <th>Prev.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Coletivo</td>
                        <td>10</td>
                        <td>07</td>
                        <td>01</td>
                        <td>02</td>
                    </tr>
                    <tr>
                        <td>Individual</td>
                        <td>10</td>
                        <td>05</td>
                        <td>04</td>
                        <td>02</td>
                    </tr>
                    <tr>
                        <td>Empresarial</td>
                        <td>10</td>
                        <td>00</td>
                        <td>00</td>
                        <td>00</td>
                    </tr>
                    </tbody>
                </table>

        </div>

        <div class="col-md-7">
            <div class="graficos" style="display: flex; align-items: center; background-color: #123449; color: white; border-radius: 5px; justify-content: space-between;">
                <div class="gauge-container" style="margin-right: 10px;">
                    <div id="chart_div1"></div>
                </div>
                <div class="gauge-container" style="margin-right: 10px;">
                    <div id="chart_div2"></div>
                </div>
                <div class="gauge-container">
                    <div id="chart_div3"></div>
                </div>
            </div>
        </div>



    </section>

    <section class="grafico_anual" style="width:100%;height:400px;margin-bottom:20px;margin-top:15px;background-color:#123449;">
        <h3 class="text-center text-white">VENDA ANUAL</h3>
        <canvas id="anual" width="1400" height="350"
                data-label-anual=""
                data-label-anual-coletivo=""
                data-label-anual-individual=""
        ></canvas>
    </section>

@stop


@section('js')
    <script>

        $(document).ready(function(){

            google.charts.load('current', { 'packages': ['gauge'] });
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                var data1 = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Vendas', 80]
                ]);

                var options1 = {
                    width: 200, height: 200,
                    redFrom: 0, redTo: 25,
                    yellowFrom: 25, yellowTo: 75,
                    greenFrom: 75, greenTo: 100,
                    minorTicks: 5
                };

                var chart1 = new google.visualization.Gauge(document.getElementById('chart_div1'));
                chart1.draw(data1, options1);

                var data2 = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Cadastro', 55]
                ]);

                var options2 = {
                    width: 200, height: 200,
                    redFrom: 0, redTo: 25,
                    yellowFrom: 25, yellowTo: 75,
                    greenFrom: 75, greenTo: 100,
                    minorTicks: 5
                };

                var chart2 = new google.visualization.Gauge(document.getElementById('chart_div2'));
                chart2.draw(data2, options2);

                var data3 = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Previsao', 68]
                ]);

                var options3 = {
                    width: 200, height: 200,
                    redFrom: 0, redTo: 25,
                    yellowFrom: 25, yellowTo: 75,
                    greenFrom: 75, greenTo: 100,
                    minorTicks: 5
                };

                var chart3 = new google.visualization.Gauge(document.getElementById('chart_div3'));
                chart3.draw(data3, options3);
            }






            let anual = $("#anual");
            new Chart(anual, {
                type: 'bar',
                data: {
                    labels: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                    datasets: [{
                        label: 'Individual',
                        data: [2,4,6,9,5,3,2,4,5,6,7,4],
                        backgroundColor: [
                            'rgba(255,255,255, 0.9)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)'

                        ],
                        //borderWidth: 1
                    },
                        {
                            label: 'Coletivo',
                            data: [3,5,7,8,2,4,6,7,9,2,1,8],
                            backgroundColor: [
                                'rgba(255,255,255,0.5)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)'
                            ],
                            //borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: false,
                    scales: {
                        y: {
                            ticks: {
                                color: "white",
                                stepSize: 1,
                                beginAtZero: true
                            }
                        },
                        x: {
                            ticks: {
                                color: "white",
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: "white",
                            }
                        }
                    },
                }
            });










        });
        
    </script>
@stop

@section('css')
    <style>
        .grafico_anual {height:300px;background-color: #FFF;margin-top: 5px;}
        #chart_div table tr {
            display:flex !important;
        }
        #ranking_user {
            height: 400px;
            max-height: 400px;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: #123449 transparent; /* Cor da barra de rolagem */
            background-color:#123449;
            color:#FFF;
        }

        #ranking_user::-webkit-scrollbar {
            width: 12px; /* Largura da barra de rolagem */
            background-color:#FFF;
        }

        #ranking_user::-webkit-scrollbar-thumb {
            background-color: #123449; /* Cor da alça da barra de rolagem */
            border-radius: 6px; /* Arredondar a alça */
        }

        #ranking_user::-webkit-scrollbar-track {
            background: transparent; /* Cor do fundo da barra de rolagem */
        }

    </style>
@stop
