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

@section('content')
    <section class="content">
       
            <div class="row">
                <!--1º Card-->
                <div class="col-3">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>New Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!--Fim 1º Card-->

                <!--2º Card-->
                <div class="col-3">

                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>
                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!--Fim 2º Card-->

                <!--3º Card-->
                <div class="col-3">

                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!--Fim 3º Card-->

                <!--4º Card-->
                <div class="col-3">

                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!--Fim 4º Card-->
            </div>
        

        
           <!-- 2º Linha -->
        


<div class="row">

    <div class="col-9">

        <section class="grafico_anual" style="width:100%;height:400px;background-color: #FFF;">
                <div class="flex" style="align-items:center;align-content:center;">
                        <div class="row py-1">
                            <div class="col-6">
                                <span style="font-weight:bold;display:flex;align-self:center;margin:8px 0 0 0;">VENDA ANUAL</span>

                            </div>
                            <div class="col-6">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#">Hoje</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Mes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Ano</a>
                                    </li>
                                    
                                </ul>    
                            </div>
                        </div>    
                    </div>    




            <canvas id="anual" width="1240px" height="350" data-label-anual="" data-label-anual-coletivo="" data-label-anual-individual=""></canvas>
        </section>

    </div>

    <div class="col-3" style="height:400px;padding-right:10px;padding-left:10px;border-radius:5px;">
        <div style="background-color:green;height:400px;">
                <h4>Planos</h4>
                <ul>
                    <li>Individual</li>
                    <li>Coletivo Por Adesão</li>
                    <li>PME</li>
                    <li>Super Simples</li>
                    <li>Sindipão</li>
                    <li>Coletivo Integrado</li>
                    <li>Sindimaco</li>
                    <li>Super Simples Integrado</li>
                    <li>Super Simples Pleno</li>
                    <li>Sincofarma</li> 
                </ul>
            
        </div>    
    </div>
</div> 
<!-- Fim 2º Linha -->


<!-- 3º Linha -->

<div class="row my-2">
    
    <div class="col-9">
        
        <div class="row">
            <div class="col-6" style="height:400px;">
                <div style="background-color:#FFF;height:400px;">
                    <canvas id="pizza_grafico" height="300" data-chart-background-color="" data-chart-quantidade-valores="" data-chart-label-leads=""></canvas>
                </div>
           </div>
            <div class="col-6" style="height:400px;">
                <div style="background-color:#FFF;height:400px;">
                    <canvas id="pie_grafico" height="300" data-chart-background-color="" data-chart-quantidade-valores="" data-chart-label-leads=""></canvas>
                </div>    
            </div>
        </div>

    </div>
    
    <div class="col-3" style="height:400px;">

        <div style="height:400px;background-color: #FFF;border-radius:5px;">
                <h4>Cidades</h4>
                <ul>
                    <li>Individual</li>
                    <li>Coletivo Por Adesão</li>
                    <li>PME</li>
                    <li>Super Simples</li>
                    <li>Sindipão</li>
                    <li>Coletivo Integrado</li>
                    <li>Sindimaco</li>
                    <li>Super Simples Integrado</li>
                    <li>Super Simples Pleno</li>
                    <li>Sincofarma</li> 
                </ul>
            
        </div>    





    </div>


</div>
<!-- Fim 3º Linha -->

<!-- 4º Linha -->
<div class="row">
    <div class="col-9">
        <div style="background-color:white;border-radius:5px;">
            <canvas id="ranking_grafico" class="grafico-com-imagens" data-chart-background-color="" data-chart-quantidade-valores="" data-chart-label-leads=""></canvas>
        </div>
    </div>
    <div class="col-3" style="">

    <div class="card" style="height:614px;">
        <div class="card-header">
            <h3 class="card-title">Carousel</h3>
        </div>

        <div class="card-body" style="height:614px;">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1" class=""></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2" class=""></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-custom-icon" aria-hidden="true">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-custom-icon" aria-hidden="true">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

</div>    













    </div>


</div>

<!-- Fim 4º Linha -->



        










    </section>
@stop


@section('js')
    <script>
        $(document).ready(function(){

            /*** Grafico Ranking */
            let ranking = $("#ranking_grafico");

            // Função para pré-carregar uma imagem
            function loadImage(src) {
                return new Promise((resolve, reject) => {
                    let img = new Image();
                    img.onload = () => resolve(img);
                    img.onerror = reject;
                    img.src = src;
                });
            }

            var imagensRanking = [
                '/storage/users/1696421827_4iGySSimaR7PUlgwMK9UHCQ7CgEmPiTui29D8CCj.png',
                '/storage/users/qdY8DA3Pc6KAptEY4LecBA5J7nWYdwP20Mb7777.jpg',
                '/storage/users/o8ofEU5eKaDpbhrDNUbCIhyEpqHQgJbPfI6fZFZa.png',
                '/storage/users/sX7rbu4AoO6Wbs7rxAyAZuz2CaFkTrVVT7cJKUdP.jpg',
                '/storage/users/qdY8DA3Pc6KAptEY4LecBA5J7nWYdwP20MbCCPgs.jpg',
                '/storage/users/2x6pXzoiBCiWRFfB8IPEkVaRLaIsQdOCdYnIzRvg.png',
                '/storage/users/LZqiZbb4LzgGAg25mMHNBxt7eIZ8o1fkfvFVtpQ3.png',
                '/storage/users/6FjoAcgd4znFJo713Mqyyddqxj9GdhKrYj9WuAUg.jpg',
                '/storage/users/lRSL0uacPkyBgJjeLAA41axUaWIhVWuCVeJPj777.png',
                '/storage/users/XEeipiCnlR5nK2t0NiFxlP6lSrlzDBKpKBobXBIW.jpg',
            ];

            // Função para desenhar a imagem no final de cada barra
            async function drawImages(chart) {
                if (chart.canvas.classList.contains('grafico-com-imagens')) {
                    let ctx = chart.ctx;
                    let dataset = chart.data.datasets[0];
                    let xOffset = chart.width - 80; // Ajuste a posição horizontal da imagem conforme necessário

                    for (let index = 0; index < dataset.data.length; index++) {
                        let value = dataset.data[index];
                        let yOffset = chart.getDatasetMeta(0).data[index].y - 20; // Ajuste a posição vertical da imagem conforme necessário
                        //let imgSrc = '/storage/users/1696421827_4iGySSimaR7PUlgwMK9UHCQ7CgEmPiTui29D8CCj.png'; // Substitua pelo caminho da sua imagem
                        let imgSrc = imagensRanking[index];
                        try {
                            let img = await loadImage(imgSrc);
                            ctx.drawImage(img, xOffset, yOffset, 50, 40); // Ajuste o tamanho da imagem conforme necessário
                        } catch (error) {
                            console.error('Erro ao carregar a imagem', error);
                        }
                    }
                }
                
            }

            Chart.register({
                id: 'imagePlugin',
                afterDatasetsDraw: drawImages
            });

            new Chart(ranking, {
                type: 'bar',
                data: {
                    labels: ['Marcela', 'Pietra', 'Eliane', 'Giselly', 'Rebeca', 'Daiane', 'Matheus Moraes','Laura','Matheus Felipe','Anderson Prado'],
                    datasets: [{
                        label: 'My First Dataset',
                        data: [95,85,75,68,62,58,42,38,32,25],
                        fill: false,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)',
                            'rgba(98, 58, 52, 0.2)',
                            'rgba(85, 102, 34, 0.2)',
                            'rgba(74, 105, 69, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)',
                            'rgb(98, 58, 52)',
                            'rgb(85, 102, 34)',
                            'rgb(74, 105, 69)'
                        ],
                        borderWidth: 1
                    }],
                },
                options: {
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                },
                plugins: ['imagePlugin']
            });

            /*** Fim Grafico Ranking */


            /** Grafico Anual */
            let anual = $("#anual");
            new Chart(anual, {
                type: 'bar',
                data: {
                    labels: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                    datasets: [{
                        label: 'Individual',
                        data: [2,4,6,9,5,3,2,4,5,6,7,4],
                        backgroundColor: [
                            'rgba(255, 255, 0, 255)'
                        ],
                        borderColor: [
                            'rgba(255, 255, 0, 255)'

                        ],
                        //borderWidth: 1
                    },
                        {
                            label: 'Coletivo',
                            data: [3,5,7,8,2,4,6,7,9,2,1,8],
                            backgroundColor: [
                                'rgba(0, 0, 255, 255)'
                            ],
                            borderColor: [
                                'rgba(255, 255, 0, 255)'
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
                                color: "black",
                                stepSize: 1,
                                beginAtZero: true
                            }
                        },
                        x: {
                            ticks: {
                                color: "black",
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: "black",
                            }
                        }
                    },
                }
            });
            /** Fim Grafico Anual */

            /** Doughnut */
            var DATA_COUNT = 5;
            
            let leads_mes = $("#pizza_grafico");
            new Chart(
                leads_mes,
                {   
                    type: 'doughnut',
                    data: {
                    labels: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
                    datasets: [
                        {
                        label: 'Dataset 1',
                        data: [50, 10, 10, 10, 20],
                        backgroundColor: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
                        }
                    ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Doughnut Chart'
                        }
                        }
                    },
                
                }
            )
            /** Fim Dought */


            /** Pie */
            let pie_grafico = $("#pie_grafico");
            new Chart(
                pie_grafico,
                {
                    type: 'pie',
                    data: {
                    labels: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
                        datasets: [
                            {
                                label: 'Dataset 1',
                                data: [50, 10, 10, 10, 20],
                                backgroundColor: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Pie Chart'
                        }
                        }
                    }
                }
                
            )


            /** Fim Pie */




        });
    </script>
@stop