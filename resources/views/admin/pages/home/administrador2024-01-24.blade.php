@extends('adminlte::page')
@section('title', 'Dashboard')
@section('plugins.Chartjs', true)
@section('plugins.ChartGoogle', true)
@section('plugins.Leaf', true)
@section('plugins.Carousel', true)


@section('content_top_nav_right')
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content_header')
    
@stop

@section('content')

<div class="header my-2">
    <div class="container-palavras">
        <div class="word-container p-1" id="word-container">
            <div class="word">
                <sub>Vidas: 10</sub>
                <span>Hapvida</span>
                <sup>Total: R$ 1000</sup>
            </div>
            
            <div class="word">
                <sub>Vidas: 10</sub>
                <span>Qualicorp</span>
                <sup>Total: R$ 1000</sup>
            </div>
            <div class="word">
                <sub>Vidas: 10</sub>
                <span>Allcare</span>
                <sup>Total: R$ 1000</sup>
            </div>
            <div class="word">
                <sub>Vidas: 10</sub>
                <span>Alter</span>
                <sup>Total: R$ 1000</sup>
            </div>
        </div>
    </div>
    
    
    
      <div id="data-hora"></div>
    
  </div>

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


<div class="row mb-2">
    <div class="col-6">
        
        <table align="center">
            <tr valign="top">
                <td style="width: 50%;">
                
                    <div id="map" style="width: 400px; height: 300px;"></div>
                </td>
                <td style="width: 50%;">
                <div id="table_div"></div>
                </td>
            </tr>
            <tr>
                <td colSpan=2>
                <div id="chart_div" style="align: center; width: 700px; height: 300px;"></div>
                </td>
            </tr>
        </table>

    </div>
    <div class="col-6" style="height: 210px;border-radius:5px 5px;">

        <div id="piechart_3d" style="height: 210px;color:#FFF;"></div>


        
        <div style="background-color:#123449;height:390px;display:flex;flex-direction:column;">
            <div style="display:flex;justify-content: space-around;">
                <div class="gauge-container">
                    <div id="chart_div1"></div>
                </div>
                <div class="gauge-container">
                    <div id="chart_div2"></div>
                </div>
            </div>
            <div style="display:flex;justify-content: center;">
                <div class="gauge-container">
                    <div id="chart_div3"></div>
                </div>
            </div>
        </div>
        







    </div>
</div>











<div class="row">
    
    <div class="col-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Individual</p>
            </div>
            <div class="icon">
                <i class="fas fa-signal"></i>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Coletivo</p>
            </div>
            <div class="icon">
                <i class="fas fa-signal"></i>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Empresarial</p>
            </div>
            <div class="icon">
                <i class="fas fa-signal"></i>
            </div>
        </div>
    </div>



</div>


<div class="row">

    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Por Administradora
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>           
            <div class="card-body">
                <ul>
                    <li>Allcare</li>
                    <li>Alter</li>
                    <li>Qualicorp</li>
                    <li>Hapvida</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Por Cidade
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>           
            <div class="card-body">
                <ul>
                    <li>Allcare</li>
                    <li>Alter</li>
                    <li>Qualicorp</li>
                    <li>Hapvida</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Por Plano
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>           
            <div class="card-body">
                <ul>
                    <li>Allcare</li>
                    <li>Alter</li>
                    <li>Qualicorp</li>
                    <li>Hapvida</li>
                </ul>
            </div>
        </div>
    </div>



</div>


<div class="row">
    
    <div class="col-4" style="height:400px;">
    <div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-map-marker-alt mr-1"></i>
            Quantidade de Vendas Por Cidade
        </h3>

        <div class="card-tools">
            
            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>

        </div>
            <div class="card-body">
                <div id="map"></div>
            </div>

            
        </div>

    </div>

    <!-- Podio -->
    <div class="col-4 border border-width-3 border-info rounded" style="height:400px;background-color:rgba(205, 232, 215,0.5);">

        <div class="d-flex w-75 mx-auto">

            <div class="d-flex custom-subcolumn">
                <div class="d-flex flex-column">
                    <p class="text-center">
                        <img src="{{asset('storage/users/HKRnCOW5QTP4YfC7Hha6IfHho4mWCKcqtnx2nUbh.png')}}" alt="Default" width="110px" style="border-radius:50%;border:3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                    </p>
                    <p class="text-center">R$ 1,77</p>
                    <div class="custom-background">
                        <i class="fas fa-medal fa-lg custom-icon"></i>
                    </div>
                </div>
            </div>

            <div class="d-flex custom-subcolumn">
                <div class="d-flex flex-column">
                    <p class="text-center">
                        <img src="{{asset('storage/users/1696421827_4iGySSimaR7PUlgwMK9UHCQ7CgEmPiTui29D8CCj.png')}}" alt="Default" width="110px" style="border-radius:50%;border:3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                    </p>
                    <p class="text-center">R$ 1,77</p>
                    <div class="custom-background-win">
                        <i class="fas fa-trophy fa-lg custom-icon"></i>
                    </div>
                </div>
            </div>
   
            <div class="d-flex custom-subcolumn">
                <div class="d-flex flex-column">
                    <p class="text-center">
                        <img src="{{asset('storage/users/sX7rbu4AoO6Wbs7rxAyAZuz2CaFkTrVVT7cJKUdP.jpg')}}" alt="Default" width="110px" style="border-radius:50%;border:3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                    </p>
                    <p class="text-center">R$ 1,77</p>
                    <div class="custom-background">
                        <i class="fas fa-medal fa-lg custom-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FIm Podio -->




















    <div class="col-4" style="height: 400px;overflow:auto;">
        <div class="card">


        <div class="card h-100">
	        <div class="card-header">
		        <h5 class="mb-0 text-capitalize">Ranking</h5>
	        </div>
	        <div class="card-body pt-0">
		        <ul class="list-group list-group-flush">
                    @php
                        $ii=0;
                    @endphp

                    @foreach($ranking as $u)
                    @php
                        $ii++;
                    @endphp
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto d-flex align-items-center">
                                    <a href="javascript:;" class="avatar">
                                        <img src="{{$u->imagem ? asset('storage/'.$u->imagem) : asset('storage/avatar-default.jpg')}}" alt="{{$u->usuario}}" width="50px" height="50px">
                                    </a>
                                </div>
                                <div class="col ml-2">
                                    <h6 class="mb-0">
                                        <a href="javascript:;">{{$u->usuario}}</a>
                                    </h6>
                                    <span class="badge badge-info badge-sm">{{$ii}} º</span>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary btn-xs mb-0">{{$u->quantidade}}</button>
                                </div>
                            </div>
                        </li>
                    @endforeach

		        </ul>
	        </div>
        </div>
        
        </div>
    </div>


</div>


<section class="row">
        <!-- Primeira Coluna - Podios -->



        <div class="col-md-4" style="background-color:#123449;border-radius:5px;">
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


        


</section>        

<section class="grafico_anual" style="width:100%;height:400px;margin-bottom:20px;margin-top:15px;background-color: #123449;">

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
                    width: 170, height: 170,
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
                    width: 170, height: 170,
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
                    width: 170, height: 170,
                    redFrom: 0, redTo: 25,
                    yellowFrom: 25, yellowTo: 75,
                    greenFrom: 75, greenTo: 100,
                    minorTicks: 5
                };

                var chart3 = new google.visualization.Gauge(document.getElementById('chart_div3'));
                chart3.draw(data3, options3);
            }

            google.charts.load('current', {
                'packages': ['table', 'corechart'],
        
        
            });
            google.charts.setOnLoadCallback(initialize);
          

            function initialize() {
                var query = new google.visualization.Query(
                    'https://spreadsheets.google.com/pub?key=pCQbetd-CptF0r8qmCOlZGg');
                query.send(draw);
            }

            function draw(response) {
                if (response.isError()) {
                    alert('Error in query');
                }

                var ticketsData = google.visualization.arrayToDataTable([
                    ['Plano', 'Vendas'],
                    ['Individual', 100],
                    ['Corporit', 150],
                    ['Coletivo por Adesão', 80],
                    ['PME', 200],
                    ['Super Simples', 200],
                    ['Sindipão', 200],
                    ['Coletivo Integrado', 200],
                    ['Sindimaco', 200],
                ]);
                var chart = new google.visualization.ColumnChart(
                    document.getElementById('chart_div')
                );
                chart.draw(ticketsData, {'isStacked': true, 'legend': 'bottom',
                    'vAxis': {'title': 'Planos'}});

                var geoData = google.visualization.arrayToDataTable([
                    ['Nome', 'Vidas', 'Total'],
                    ['Anápolis',10,'R$ 1000'],
                    ['Goiania',10,'R$ 1000'],
                    ['Rondonópolis',10,'R$ 1000'],
                    ['Cuiabá',10,'R$ 1000'],
                    ['Três Lagoas',10,'R$ 1000'],
                    ['Dourados',10,'R$ 1000'],
                    ['Campo Grande',10,'R$ 1000'],
                    ['Brasília',10,'R$ 1000'],
                    ['Rio Verde',10,'R$ 1000'],
                    ['Bahia',10,'R$ 1000'],
                ]);

                var geoView = new google.visualization.DataView(geoData);
                geoView.setColumns([0, 1]);

                var table =
                    new google.visualization.Table(document.getElementById('table_div'));
                table.draw(geoData, {showRowNumber: false, width: '100%', height: '100%'});

       
            }


            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                let datas_3d = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['Hapvida',11],
                    ['Qualicorp',2],
                    ['Allcare',2],
                    ['Alter',2]
                ]);

                let options_3d = {
                    title: 'Por Administradora',
                    is3D: true,
                    color:'#FFF',
                    backgroundColor: '#123449',
                    titleTextStyle: {
                        color: '#fff' // Cor do texto do título
                    },
                    
                    legend: {
                        textStyle: {color: 'white', fontSize: 16}
                    }

                };

                let chart_3d = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                chart_3d.draw(datas_3d, options_3d);

            }    
            
      });







    function formatNumberWithZero(number) {
      return number < 10 ? '0' + number : number;
    }

    function atualizarDataHora() {
      const dataHoraElement = document.getElementById("data-hora");
      const dataHora = new Date();
      const diaSemana = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
      const dia = formatNumberWithZero(dataHora.getDate());
      const mes = formatNumberWithZero(dataHora.getMonth() + 1);
      const ano = dataHora.getFullYear();
      const hora = formatNumberWithZero(dataHora.getHours());
      const minuto = formatNumberWithZero(dataHora.getMinutes());
      const segundo = formatNumberWithZero(dataHora.getSeconds());

      const dataHoraString = `${diaSemana[dataHora.getDay()]}, ${dia} de outubro de ${ano} às ${hora}:${minuto}:${segundo}`;
      dataHoraElement.innerHTML = dataHoraString;
    }

    // Chame a função para exibir a data e hora inicial
    atualizarDataHora();

    // Atualize a data e hora a cada segundo
    setInterval(atualizarDataHora, 1000);

    var map = L.map('map').setView([-15, -55], 4); // Ajuste o centro e o zoom conforme necessário

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        
        
    }).addTo(map);

        var cities = [
            {
                name: 'Anápolis',
                lat: -16.326,  // Latitude de Anápolis
                lng: -48.952,  // Longitude de Anápolis
                sales: 100
            },
            {
                name: 'Goiânia',
                lat: -16.679,  // Latitude de Goiânia
                lng: -49.256,  // Longitude de Goiânia
                sales: 150
            },
            {
                name: 'Brasilia',
                lat:  -15.7801,  // Latitude de Goiânia
                lng:  -47.9292,  // Longitude de Goiânia
                sales: 150
            },
            {
                name: 'Rondonópolis',
                lat:  -16.4696,  // Latitude de Goiânia
                lng:  -54.6350,  // Longitude de Goiânia
                sales: 150
            },
            {
                name: 'Cuiaba',
                lat:  -15.5980,  // Latitude de Goiânia
                lng:  -56.0949,  // Longitude de Goiânia
                sales: 150
            },
            {
                name: 'Campo Grande',
                lat:  -20.4482,  // Latitude de Goiânia
                lng:  -54.6291,  // Longitude de Goiânia
                sales: 150
            },
            // Adicione os dados das outras cidades aqui
        ];

        // Adicione marcadores para as cidades com pop-ups
        cities.forEach(function(city) {
            var marker = L.marker([city.lat, city.lng]).addTo(map);
            marker.bindPopup('<b>' + city.name + '</b><br>Vendas: ' + city.sales).openPopup();
        });


           


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










        

        
    </script>
@stop

@section('css')
   
    <style>
        #map {display:flex;height: 310px;flex-basis:100%;}        
        .custom-column {height: 400px;background-color: rgba(205, 232, 215, 0.5);}
        .custom-subcolumn {width: 150px;align-items: flex-end;align-content: flex-end;height: 400px;}
        .rounded-image {width: 110px;border-radius: 50%;border: 3px solid #d8a200;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;}
        .custom-background {width: 100%;height: 180px;background-color: white;display: flex;align-items: center;justify-content: center;border-radius: 5px 5px 0 0;}
        .custom-background-win {width: 100%;height: 200px;background-color: gold;display: flex;align-items: center;justify-content: center;border-radius: 5px 5px 0 0;}
        .custom-icon {color: #000;font-size: 4em;margin-bottom: 10px;}

        .google-visualization-title {
      fill: #fff !important;
    }
    .google-visualization-tooltip-item-label {
      color: #fff !important;
    }

    .header {
      background-color: #123449;
      color: #fff;
      padding: 10px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .container-palavras {
      display: flex;
      flex-basis: 72%;
      
      
      overflow: hidden; /* Impede que as palavras se espalhem */
    }

    .word-container {
      display: flex;
      flex-basis:100%;
      animation: moveWords 15s linear infinite;
      white-space: nowrap; /* Evita que as palavras quebrem */
    }

    .word {
      margin-right: 80px;
      color: #00FF00;
      font-family: 'Courier New', monospace;
      display:flex;
      flex-direction:column;
    }

    
   

    .word sub,
    .word sup {
      display: inline; /* Exibe elementos em linha com o texto */
      font-size: 0.8em;

      margin-left: 80px; /* Adiciona espaço entre os elementos */
    }


    #data-hora {
      display: flex;
      flex-basis: 28%;
      color: #00FF00;
      font-size:0.875em;
      font-family: 'Courier New', monospace;
    }

    @keyframes moveWords {
      0% {
        transform: translateX(100%);
      }
      100% {
        transform: translateX(-100%);
      }
    }








    

    </style>
@stop
