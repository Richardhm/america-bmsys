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

    <section class="content">

        <!----------HEADER DASHBOARD----------->
        <section class="header mb-1">
            <!-----TABLE----->
            <div class="tabela" style="margin:0;padding:0;background-color:#123449;border-radius:5px;">
                <table class="table table-sm table-borderless w-100" style="color:white;margin-bottom:0rem;border-radius:5px;">
                    <thead>
                        <tr class="text-center border-bottom">
                            <th colspan="5">Novembro / 2022</th>
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
            <!-----FIM TABLE----->

            <!-----GRAFICOS--------->
            <div class="graficos" style="display:flex;align-items:center;background-color:#123449;color:white;border-radius:5px;">
                <div id="chart_div" style="color:#FFF;display:flex;justify-content:space-between;"></div>
            </div>
            <!-----FIM GRAFICOS----->

            <!-----CARDS--------->
            <div class="cards">

                <div class="box-body" style="flex-basis:48%;padding:5px;background-color:#123449;color:#FFF;border-radius:5px;">
                    <h5 class="text-center border-bottom">Vendas Mês</h5>
                    <div>
                        <h6></h6>
                    </div>
                </div>
                <div class="box-body" style="flex-basis:48%;padding:5px;background-color:#123449;color:#FFF;border-radius:5px;">
                    <h5 class="text-center border-bottom">Cancelados Mês</h5>     
                    <div id="cancelados">                       
                        <div class="boxer">
                            <div class="boxer-circle">
                                <svg>
                                    <circle cx="70" cy="70" r="70"></circle>
                                    <circle id="circleProgress" cx="70" cy="70" r="70"></circle>
                                </svg>
                            </div>
                            <div class="number">
                                <h2></h2>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-----CARDS--------->


        </section>
        <!----------FIM HEADER DASHBOARD----------->


        <!---------------LEMBRETES-------------------------->

            <!-- <div style="display:flex;flex-basis:100%;justify-content: space-between;box-sizing:border-box;"> -->

                
                    <!-- <a href="/admin/clientes/pf" class="conteudo"> 
                                            
                        <div class="conteudo_left">
                                                    
                            <div class="conteudo_left_icone">
                                <i class="fas fa-list" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>

                            <div class="conteudo_left_descricao">
                                <span>Tarefas</span>
                                <span class="text-success">Hoje</span>
                            </div>

                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">10</p>
                        </div>

                    </a> -->

                    <!-- <a href="/admin/clientes/pf?ac=tarefa_atrasada" class="conteudo">                       
                        
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-tasks" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Tarefas</span>
                                <span class="text-danger">Atrasadas</span>
                            </div>
                        </div>

                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>

                    </a>     -->

                    <!-- <a href="/admin/leads/pessoa_fisica" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-calendar-day" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Plantão Vendas</span>
                                <span class="text-success">Hoje</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">0</p>
                        </div>
                    </a> -->


                    <!-- <a href="/admin/leads/pessoa_fisica" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-calendar-times" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Plantão Vendas</span>
                                <span class="text-danger">Atrasadas</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">0</p>
                        </div>
                    </a> -->
            <!-- </div> -->

            <!-- <div style="display:flex;flex-basis:100%;justify-content: space-between;box-sizing:border-box;margin-top:10px;"> -->

                
                    <!-- <a href="/admin/leads/pessoa_fisica?ac=atendimento_iniciado" class="conteudo">                       
                        <div class="conteudo_left">
                                                    
                            <div class="conteudo_left_icone">
                                <i class="far fa-handshake" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>

                            <div class="conteudo_left_descricao">
                                <span>Atendimento Iniciado</span>
                                <span class="text-success">Hoje</span>
                            </div>

                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>
                    </a> -->

                    <!-- <a href="/admin/leads/pessoa_fisica?ac=atendimento_iniciado" class="conteudo">                       
                        
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-handshake-slash" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Atendimento Iniciado</span>
                                <span class="text-danger">Atrasadas</span>
                            </div>
                        </div>

                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>

                    </a>     -->

                    <!-- <a href="/admin/leads/pessoa_fisica?ac=prospeccaohj" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-thumbs-up" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Prospecção</span>
                                <span class="text-success">Hoje</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div> 
                    </a>-->


                    <!-- <a href="/admin/leads/pessoa_fisica?ac=prospeccaoat" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-thumbs-down" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Prospecção</span>
                                <span class="text-danger">Atrasadas</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>
                    </a> -->
            <!-- </div> -->
            
            <!-- <div style="display:flex;flex-basis:100%;justify-content: space-between;box-sizing:border-box;margin-top:10px;"> -->
                    <!-- <a href="/admin/clientes/pf?ac=interessado_frio" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-star fa-xs" style="color:rgb(255,165,0);font-size:1em;"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Interessado</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>
                    </a> -->

                    <!-- <a href="/admin/clientes/pf?ac=interessado_morno" class="conteudo">                       
                        
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-star fa-xs" style="color:rgb(255,165,0);font-size:1em;"></i>
                                <i class="fas fa-star fa-xs" style="color:rgb(255,165,0);font-size:1em;"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Interressado</span>
                            </div>
                        </div>

                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>

                    </a>     -->

                    <!-- <a href="/admin/clientes/pf?ac=interessado_quente" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-star fa-xs" style="color:rgb(255,165,0);font-size:1em;"></i>
                                <i class="fas fa-star fa-xs" style="color:rgb(255,165,0);font-size:1em;"></i>
                                <i class="fas fa-star fa-xs" style="color:rgb(255,165,0);font-size:1em;"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Interessado</span>
                                                  </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>
                    </a> -->


                    <!-- <a href="/admin/clientes/pf?ac=aguardando_documentacao" class="conteudo">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-file-medical-alt" style="font-size:1.8em;color:rgb(255,165,0);"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Aguardando Doc.</span>
                                <span class="text-danger">Atrasadas</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>
                    </a> -->
            <!-- </div> -->

            <!-----LINHA 04------>    
            <div class="linha_04">

                <a href="/admin/contratos/pf?ac=aguardando_boleto" class="conteudo" style="background-color:#123449;">
                    <div style="display:flex;flex-basis:70%;">
                        <span style="display:flex;font-size:1.3em;border:1px solid rgba(255,255,255,0.1);border-radius:5px;color:rgb(249,179,0);flex-basis:35%;height:62px;align-items:center;justify-content:center;">0</span>
                        <div style="display:flex;flex-direction:column;flex-basis:65%;margin-left:10px;">
                            <span>Aguardando</span>
                            <span>Boleto</span>
                        </div>
                    </div>
                    <div  style="display:flex;flex-basis:30%;flex-direction: column;align-content:space-around;justify-content: space-around;">
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">R$ 1000</span>
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">Vidas: 10</span>    
                    </div>
                </a>
                
                <a href="/admin/contratos/pf?ac=pagamento_adesao" class="conteudo" style="background-color:#123449;">
                    <div style="display:flex;flex-basis:70%;">
                        <div style="display:flex;font-size:1.3em;border:1px solid rgba(255,255,255,0.1);border-radius:5px;color:rgb(249,179,0);flex-basis:35%;height:62px;align-items:center;justify-content:center;">0</div>
                        <div style="display:flex;flex-direction:column;flex-basis:65%;margin-left:10px;">
                            <span>Pagamento</span>
                            <span>Adesão</span>
                        </div>
                    </div>
                    <div  style="display:flex;flex-basis:30%;flex-direction: column;align-content:space-around;justify-content: space-around;">
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">R$ 1000</span>
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">Vidas: 10</span>    
                    </div>
                </a>

                <a href="/admin/contratos/pf?ac=pagamento_vigencia" class="conteudo" style="background-color:#123449;">
                    <div style="display:flex;flex-basis:70%;">
                    <div style="display:flex;font-size:1.3em;border:1px solid rgba(255,255,255,0.1);border-radius:5px;color:rgb(249,179,0);flex-basis:35%;height:62px;align-items:center;justify-content:center;">0</div>
                        <div style="display:flex;flex-direction:column;flex-basis:65%;margin-left:10px;">
                            <span>Pagamento</span>
                            <span>Vigência</span>
                        </div>
                    </div>
                    <div  style="display:flex;flex-basis:30%;flex-direction: column;align-content:space-around;justify-content: space-around;">
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">R$ 1000</span>
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">Vidas: 10</span>    
                    </div>
                </a>

                <a href="/admin/contratos/pf?ac=pagamento_individual" class="conteudo" style="background-color:#123449;">
                    <div style="display:flex;flex-basis:70%;">
                    <div style="display:flex;font-size:1.3em;border:1px solid rgba(255,255,255,0.1);border-radius:5px;color:rgb(249,179,0);flex-basis:35%;height:62px;align-items:center;justify-content:center;">0</div>
                        <div style="display:flex;flex-direction:column;flex-basis:65%;margin-left:10px;">
                            <span>Pagamento</span>
                            <span>Individual</span>
                        </div>
                    </div>
                    <div  style="display:flex;flex-basis:30%;flex-direction: column;align-content:space-around;justify-content: space-around;">
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">R$ 1000</span>
                        <span style="display:flex;justify-content: flex-end;margin-right:6px;">Vidas: 10</span>    
                    </div>
                </a>
                

            </div>
            <!-----FIM LINHA 04------>


            <div style="display:flex;flex-basis:100%;justify-content: space-between;box-sizing:border-box;margin-top:10px;">

                
                    <a href="/admin/contratos/pf?ac=contratos_finalizados" class="conteudo" style="background-color:#123449;">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-file-signature" style="font-size:1.5em;"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Contratos Finalizados</span>
                            </div>
                        </div>
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>                       
                    </a>

                    <a href="/admin/contratos/pf?ac=contratos_finalizados" class="conteudo" style="background-color:#123449;">                         
                        
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-file-contract"  style="font-size:1.5em;"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span style="margin-left:5px;">Contratos Pendentes</span>      
                            </div>
                        </div>

                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">000</p>
                        </div>

                    </a>    

                    <a href="/admin/leads/pessoa_fisica?ac=sem_contato" class="conteudo" style="background-color:#123449;">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-file-signature" style="font-size:1.5em;"></i>
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Leads Sem Contato</span>
                            </div>
                        </div>             
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">00</p>
                        </div>

                    </a>


                    <a href="/admin/leads/pessoa_fisica?ac=sem_contato" class="conteudo" style="background-color:#123449;">                       
                        <div class="conteudo_left">
                            <div class="conteudo_left_icone">
                                <i class="fas fa-user-slash" style="font-size:1.5em;"></i>    
                            </div>
                            <div class="conteudo_left_descricao">
                                <span>Clientes Perdidos</span>
                            </div>
                        </div>             
                        
                        <div class="conteudo_right">
                            <p style="font-size:1.4em;">0</p>
                        </div>
                    </a>
            </div>






            

        <!---------------------DETALHES----------------------------->
        <section class="detalhes">

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex text-center justify-content-center">Leads no Mês</h6>
                <h6 class="py-1 px-2 text-center" id="resultado_aqui_leads_mes"></h6>
                <div class="detalhes-grafico">
                    <canvas id="leads_mes" width="300" height="180" 
                        data-chart-background-color="" 
                        data-chart-quantidade-valores=""
                        data-chart-label-leads=""
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                   
                </div>
             </div>   
                
             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex text-center justify-content-center">
                    <span class="text-center">Contratos no mês</span>
                    
                </h6>
                <h6 class="py-1 px-2 text-center">
                    <span id="resultado_aqui_contratos_mes"></span>
                </h6>
                <div class="detalhes-grafico">
                    <canvas id="contratos_mes" width="300" height="180"
                        data-chart-background-color="" 
                        data-contratos-mes=""
                        data-contratos-label=""
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                    

                </div>
             </div>   

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex justify-content-center">
                    <span class="text-center">Venda por plano vidas</span>
                </h6>
                <h6 class="py-1 px-2 d-flex justify-content-center">
                    <span id="resultado_aqui_vendas_planos_vidas"></span>
                </h6>
                <div class="detalhes-grafico">
                    <canvas id="vendas_por_planos_vidas" 
                    data-vendas-por-planos-vidas-quantidade=""
                    data-vendas-por-planos-vidas-label=""
                    data-chart-background-color=""
                    width="300" height="180"></canvas>
                </div>
                <div class="detalhes-porcentagem">
                    
                    <div class="d-flex">
                        <p style="display:flex;flex-basis:9%;align-items: center;justify-content: center;">
                            <span style="width:10px;height:10px;display:block;background-color:white;border-radius:50%;"></span>
                        </p>
                        <p style="display:flex;flex-basis:47%;">Empresarial</p> 
                        <p style="flex-basis:22%;text-align:center;">0</p> 
                        <p style="flex-basis:22%;text-align:right;">+55%</p>  
                    </div>    
                    

                </div>
             </div>   

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex justify-content-center">
                    <span>Venda plano valor</span>
                </h6>
                <h6 class="py-1 px-2 d-flex justify-content-center" id="resultado_venda_por_plano_valor">

                </h6>
                <div class="detalhes-grafico">
                    <canvas id="vendas_por_planos_valor" width="300" height="180"
                    data-vendas-por-planos-valor-quantidade=""
                    data-vendas-por-planos-valor-label=""
                    data-chart-background-color=""
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                   
                    <div class="d-flex">
                        <p style="display:flex;flex-basis:9%;align-items: center;justify-content: center;">
                            <span style="width:10px;height:10px;display:block;background-color:white;border-radius:50%;"></span>
                        </p>
                        <p style="display:flex;flex-basis:47%;">Empresarial</p> 
                        <p style="flex-basis:22%;text-align:center;">R$ 0</p> 
                        <p style="flex-basis:22%;text-align:right;">+55%</p>  
                    </div>
                </div>
             </div>   
        </section>    
        <!---------------------FIM DETALHES------------------------->    
        <section class="detalhes">

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex">
                    <span style="flex-basis:66%;">Venda Coletivo Administrador</span>
                </h6>
                <div class="detalhes-grafico">
                    <canvas id="vendas_coletivo_por_administradora" width="300" height="180"
                        data-vendas-por-administradoras-quantidade=""
                        data-vendas-por-administradoras-label=""
                        data-chart-background-color=""
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                  
                   
                </div>
             </div>

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex">
                    <span style="flex-basis:38%;">Ticket Médio Mês</span>
                    
                </h6>
                <h6 class="py-1 px-2" id="resultado_ticket_medio_mes">

                </h6>
                <div class="detalhes-grafico">
                    <canvas id="ticket_medio_mes" width="300" height="180" 
                        data-ticket-medio-label=""
                        data-ticket-media-quantidade=""  
                        data-chart-background-color=""  
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                   
                    <div class="d-flex">
                        <p style="display:flex;flex-basis:9%;align-items: center;justify-content: center;margin:0px;padding:0px;">
                            <span style="width:10px;height:10px;display:block;background-color:white;border-radius:50%;"></span>
                        </p>
                        <p style="display:flex;flex-basis:47%;margin:0px;padding:0px;">Empresarial</p> 
                        <p style="flex-basis:22%;text-align:center;margin:0px;padding:0px;">R$ 0</p> 
                        <p style="flex-basis:22%;text-align:right;margin:0px;padding:0px;">+55%</p>  
                    </div>    
                    

                </div>
             </div>   

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex">
                    <span style="flex-basis:60%;">Venda por faixa etária mês</span>
                    <span style="flex-basis:40%;text-align:right;" id="resultado_venda_por_faixa_etaria_mes"></span>
                </h6>
                <div class="detalhes-grafico">
                    <canvas id="venda_por_faixa_etaria_mes" width="300" height="180"
                        data-vendas-por-faixa-etaria-label=""
                        data-vendas-por-faixa-etaria-quantidade=""
                        data-chart-background-color=""  
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                   

                </div>
             </div>   

             <div class="cards-detalhes" style="background-color:#123449;">
                <h6 class="py-1 px-2 d-flex">
                    <span style="flex-basis:50%;">Taxa Conversão</span>
                    <span style="flex-basis:50%;text-align:right;" id="resultado_taxa_conversao"></span>
                </h6>
                <div class="detalhes-grafico">
                    <canvas id="taxa_conversao" width="300" height="180"
                    data-taxa-conversao-label=""
                    data-taxa-conversao-quantidade=""
                    data-chart-background-color=""  
                    ></canvas>
                </div>
                <div class="detalhes-porcentagem">
                  
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

    

    </section>

@stop

@section('css')
    <style>   
        .conteudo {display:flex;flex-basis:24%;color:white;border-radius:5px;background-color:rgb(0,0,0,0.3);max-height:80px;box-sizing:border-box;padding:10px;}
        .conteudo_left {display:flex;flex-basis:80%;align-items: center;justify-content:center;}
        .conteudo_left_icone {display:flex;flex-basis:30%;font-size:1.3em;border:1px solid rgba(255,255,255,0.1);border-radius:5px;color:rgb(249,179,0);height:62px;align-items:center;justify-content:center;}
        .conteudo_left_descricao {display:flex;flex-direction:column;margin-left:10px;flex-basis:69%;}
        .conteudo_right {display:flex;flex-basis:20%;align-items:flex-end;justify-content: flex-end;}
        .detalhes {display: flex;flex-basis: 100%;margin-top: 15px;align-items: stretch;justify-content: space-between;}
        .detalhes .cards-detalhes {padding:5px;margin-bottom:1px;border-radius:5px;flex-basis: 24%;background-color:rgba(0,0,0,0.3);color:#FFF;}
        .detalhes .detalhes-porcentagem {display:flex;flex-direction: column;margin-top: 15px;}
        .detalhes .detalhes-grafico {display: flex;justify-content: center;}
        .detalhes-porcentagem div {display: flex;justify-content: space-between;}
        .header {display:flex;justify-content: space-between;max-height: 200px;}
        .tabela {flex-basis:24.4%;margin-bottom: 0 !important;}
        .tabela table {flex-basis:100%;}
        .table {min-height:200px !important;}
        .graficos {margin:0 1%;flex-basis:50.4%;display: flex;}
        .cards {flex-basis:24.5%;display: flex;justify-content: space-between;}
        .vendas_mes {background-color: white;flex-basis:48%;display: flex;flex-direction: column;}
        .cancelados_mes {background-color: white;flex-basis: 48%;display: flex;}       
        .lembretes {margin-top: 10px;display:flex;justify-content: space-between;flex-direction: column;}
        .lembretes article {margin-top: 5px;flex-basis: 100%;display: flex;justify-content: space-between;}
        .lembretes article div {display: flex;flex-direction: column;flex-basis: 24%;background-color: white;}
        .lembretes div span:nth-of-type(1){display: flex;font-size:1.1em;}
        .lembretes div span:nth-of-type(2){display: flex;justify-content: center;font-size:1.1em;}
        .lembretes div span:nth-of-type(3){display: flex;justify-content: end;font-size:1.1em;}
        .lembretes main {display: flex;margin-top: 5px;}
        .lembretes main div {display: flex;background-color: white;flex-basis: 25%;justify-content: space-between;padding:10px 0;}
        .lembretes main div:nth-child(2) {margin:0 1%;}
        .lembretes main div:nth-child(3) {margin:0 1% 0 0;}
        .linha_04 {display: flex;margin-top:10px;flex-basis:100%;justify-content: space-between;box-sizing:border-box;}
        .linha_04 section {display: flex;background-color:#FFF;flex-basis: 25%;flex-direction: column;}
        .linha_04 section div {display: flex;justify-content: space-between;} 
        .linha_04 section .title {display: flex;justify-content: center;}
        .linha_04 section span:nth-child(3) {display: flex;justify-content: end;}
        .linha_04 section:nth-child(2) {margin:0 1%;}
        .linha_04 section:nth-child(3) {margin:0 1% 0 0;}
        .grafico_anual {height:300px;background-color: #FFF;margin-top: 5px;}
        #cancelados {position:relative;}
        .boxer {position:absolute;top:40%;left:50%;transform:translate(-50%,-7%);border-radius:5px;padding:15px;}
		.boxer-circle svg {width:150px;height:150px;position:relative;}
		#cancelados circle {width:150px;height:150px;fill:none;stroke:black;stroke-width:10;transform:translate(5px,5px);stroke-dasharray:440;stroke-dashoffset:440;}
		#cancelados circle:nth-child(1) {stroke-dashoffset:0;stroke:rgb(102,102,102);}
		#cancelados circle:nth-child(2) {stroke:dodgerblue;transition: stroke-dashoffset 0.8s;}
		.number {position:absolute;top:0;left:0;width:100%;height:100%;display:flex;justify-content:center;align-items:center;color:white;font-size:2.4em;}
        #chart_div tr td:nth-child(2) div div div {left:45px !important;}
        #chart_div tr td:nth-child(3) div div div {left:103px !important;}
        .link-down {width:100px;text-align:center;padding:10px 0;background-color:rgba(255,255,255,0.2);border-radius:5px;font-size:1.1em;}
        .link-down:hover {cursor:pointer;}
    </style>
@stop

@section('js')
    <script>
        google.charts.load('current', {'packages':['gauge']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Vendas', 80],
                ['Cadastro', 55],
                ['Previsao', 68]
            ]);
            var options = {
                width: 800, height: 200,
                redFrom: 0, redTo: 25,
                yellowFrom:25, yellowTo: 75,
                greenFrom:75,greenTo:100,
                minorTicks: 5
            };
            var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
            chart.draw(data, options);
        }

        let circle = document.querySelector("#circleProgress");
        let number = 70;
        document.querySelector('.number').innerHTML = number + "%"
        circle.style.strokeDashoffset = 440 - (440 * number) / 100;

        $(function(){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
        




            let leads_mes = $("#leads_mes");
            new Chart(
                leads_mes,
                {   
                    "type":"doughnut",
                    "data":{
                        "datasets":[{
                            "label":leads_mes.data('chart-label-leads').split("|"),
                            "data":leads_mes.data('chart-quantidade-valores').split("|"),
                            "backgroundColor":leads_mes.data('chart-background-color').split("|")
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_aqui_leads_mes").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#leads_mes").mouseout(function(){
                                            $("#resultado_aqui_leads_mes").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )

            


            
            let contratos_mes = $("#contratos_mes");
            new Chart(
                contratos_mes,
                {   
                    "type":"doughnut",
                    "data":{
                        "datasets":[{
                            "label":contratos_mes.data('contratos-label').split("|"),
                            "data":contratos_mes.data('contratos-mes').split("|"),
                            "backgroundColor":contratos_mes.data('chart-background-color').split("|")
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_aqui_contratos_mes").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#contratos_mes").mouseout(function(){
                                            $("#resultado_aqui_contratos_mes").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )

           
            let vendas_por_planos = $("#vendas_por_planos_vidas");
            new Chart(
                vendas_por_planos,  
                {   
                    "type":"doughnut",
                    "data":{
                        "datasets":[{
                            "label":vendas_por_planos.data('vendas-por-planos-vidas-label').split("|"),
                            "data":vendas_por_planos.data('vendas-por-planos-vidas-quantidade').split("|"),
                            "backgroundColor":vendas_por_planos.data('chart-background-color').split('|')
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_aqui_vendas_planos_vidas").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#vendas_por_planos_vidas").mouseout(function(){
                                            $("#resultado_aqui_vendas_planos_vidas").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )

            

            let vendas_por_plano_valor = $("#vendas_por_planos_valor");    
            new Chart(
                vendas_por_plano_valor,
                {   
                    "type":"doughnut",
                    "data":{
                        //"labels":vendas_por_plano_valor.data('vendas-por-planos-valor-label').split("|"),
                        "datasets":[{
                            "label":vendas_por_plano_valor.data('vendas-por-planos-valor-label').split("|"),
                            "data":vendas_por_plano_valor.data('vendas-por-planos-valor-quantidade').split("|"),
                            "backgroundColor":vendas_por_plano_valor.data('chart-background-color').split("|")
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_venda_por_plano_valor").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#vendas_por_planos_valor").mouseout(function(){
                                            $("#resultado_venda_por_plano_valor").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )
            
            let vendas_coletivo_por_administradora = $("#vendas_coletivo_por_administradora");
            new Chart(
                vendas_coletivo_por_administradora,
                {   
                    "type":"doughnut",
                    "data":{
                        //"labels":vendas_coletivo_por_administradora.data('vendas-por-administradoras-label').split("|"),
                        "datasets":[{
                            "label":vendas_coletivo_por_administradora.data('vendas-por-administradoras-label').split("|"),
                            "data":vendas_coletivo_por_administradora.data('vendas-por-administradoras-quantidade').split("|"),
                            "backgroundColor":vendas_coletivo_por_administradora.data('chart-background-color').split("|")
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_venda_coletivo_por_administrador").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#vendas_coletivo_por_administradora").mouseout(function(){
                                            $("#resultado_venda_coletivo_por_administrador").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )
            
            
            
            let ticketMedio = $("#ticket_medio_mes");
            new Chart(
                ticketMedio,
                {   
                    "type":"doughnut",
                    "data":{
                        //"labels": ticketMedio.data('ticket-medio-label').split("|"),
                        "datasets":[{
                            "label":ticketMedio.data('ticket-medio-label').split("|"),
                            "data":ticketMedio.data('ticket-media-quantidade').split("|"),
                            "backgroundColor":ticketMedio.data('chart-background-color').split("|")
                    }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        console.log(tooltipItems);
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_ticket_medio_mes").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#ticket_medio_mes").mouseout(function(){
                                            $("#resultado_ticket_medio_mes").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )
            
            let vendas_por_faixa_etaria = $("#venda_por_faixa_etaria_mes")
            new Chart(
                vendas_por_faixa_etaria,
                {   
                    "type":"doughnut",
                    "data":{
                        //"labels":vendas_por_faixa_etaria.data('vendas-por-faixa-etaria-label').split("|"),
                        "datasets":[{
                            "label":vendas_por_faixa_etaria.data('vendas-por-faixa-etaria-label').split("|"),
                            "data":vendas_por_faixa_etaria.data('vendas-por-faixa-etaria-quantidade').split("|"),
                            "backgroundColor":vendas_por_faixa_etaria.data('chart-background-color').split("|")
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        console.log(tooltipItems);
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_venda_por_faixa_etaria_mes").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]);
                                        $("#venda_por_faixa_etaria_mes").mouseout(function(){
                                            $("#resultado_venda_por_faixa_etaria_mes").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )
            
            let taxa_conversao = $("#taxa_conversao");
            new Chart(
                taxa_conversao,
                {   
                    "type":"doughnut",
                    "data":{
                        //"labels": taxa_conversao.data("taxa-conversao-label").split("|"),
                        "datasets":[{
                            "label":taxa_conversao.data('taxa-conversao-label').split("|"),
                            "data":taxa_conversao.data('taxa-conversao-quantidade').split("|"),
                            "backgroundColor":taxa_conversao.data('chart-background-color').split("|")
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        console.log(tooltipItems);
                                        let resultado = tooltipItems[0].dataset.label[tooltipItems[0].dataIndex]
                                        $("#resultado_taxa_conversao").html(resultado+": "+tooltipItems[0].dataset.data[tooltipItems[0].dataIndex]+"%");
                                        $("#taxa_conversao").mouseout(function(){
                                            $("#resultado_taxa_conversao").html('');
                                        });
                                        return resultado;
                                    },
                                }
                            }
                        }
                    }
                }
            )

           /************************************************************ 1º Velocimetro ***********************************************************************************/
            const vendido = $("#vendido");            
            new Chart(
                vendido,
                {
                    type: 'doughnut',
                    data: {
                        labels: ["Ruim","Regular","Bom","Otimo"],
                        datasets: [{
                            label: 'Cadastrados',
                            data: [25,25,25,25],
                            backgroundColor: [
                                'rgba(255, 26, 104, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(50, 205, 50, 1)',
                            ],
		                    needleValue:vendido.data('needle-value-vendido'),
                            borderColor: 'white',
                            borderWidth: 2,
                            cutout:'95%',
                            circumference:180,
                            rotation:270,
                            borderRadius:5
                        }]
                    },
                    options: {
                        
                        scales: {},
		                plugins: {

                            legend: {
                                labels: {
                                    fontColor: "red"
                                }
                            }



                            // legend: {
                            // 	display:false
                            // },
                            // tooltip: {
                            // 	yAlign:'bottom',
                            // 	displayColors:false,
                            // 	callbacks: {
                            // 		label: function(tooltipItem,data,value) {
                            // 			const tracker = tooltipItem.dataset.needleValue;
                            // 			return `Tracker Score: ${tracker} %`;
                            // 		}
                            // 	}
                            // }
		                }
	                },
	                plugins: [{
		                afterDatasetDraw(chart,args,options) {
                            const { ctx,config,data,chartArea: {top,bottom,left,right,width,height} } = chart;
                            ctx.save();		
                            const needleValue = data.datasets[0].needleValue;
                            const dataTotal = data.datasets[0].data.reduce((a,b)=>a+b,0);
                            const angle = Math.PI + (1 / dataTotal * needleValue * Math.PI)
                            const cx = width / 2;
                            const cy = chart._metasets[0].data[0].y;			
                            ctx.translate(cx,cy);
                            ctx.rotate(angle);
                            ctx.beginPath();
                            ctx.moveTo(0,-2);
                            ctx.lineTo(height - (ctx.canvas.offsetTop - 110),0);
                            ctx.lineTo(0,2);
                            ctx.fillStyle = "#FFF";
                            ctx.fill();
                            ctx.restore();
                            ctx.beginPath();
                            ctx.arc(cx,cy,5,0,10);	
                            ctx.fill();
                            ctx.restore();
                            ctx.font = '5px Helvetica';
                            ctx.margin = "30px 0 0 0";
                            ctx.fillStyle = '#FFF';
                            ctx.fillText(needleValue +'%',cx,cy);
                            ctx.textAlign = 'center';
                            ctx.restore();
                        }
                    }]
                }
            );
           /*************************************************************Fim **********************************************************************************/     
    

        const cadastrado = $("#cadastrado");
        
        new Chart(
            cadastrado,
            {
                type: 'doughnut',
                data: {
                    labels: ["Ruim","Regular","Bom","Otimo"],
                    datasets: [{
                        label: 'Cadastrados',
                        data: [25,25,25,25],
                        backgroundColor: [
                            'rgba(255, 26, 104, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(50, 205, 50, 1)',
                        ],
		                needleValue:65,
                        borderColor: 'white',
                        borderWidth: 2,
                        cutout:'95%',
                        circumference:180,
                        rotation:270,
                        borderRadius:5
                    }]
                },
                options: {
                    scales: {},
		            plugins: {
                    // legend: {
                    // 	display:false
                    // },
                    // tooltip: {
                    // 	yAlign:'bottom',
                    // 	displayColors:false,
                    // 	callbacks: {
                    // 		label: function(tooltipItem,data,value) {
                    // 			const tracker = tooltipItem.dataset.needleValue;
                    // 			return `Tracker Score: ${tracker} %`;
                    // 		}
                    // 	}
                    // }
		        }
	        },
	        plugins: [{
		        afterDatasetDraw(chart,args,options) {
                    const { ctx,config,data,chartArea: {top,bottom,left,right,width,height} } = chart;
                    ctx.save();		
                    const needleValue = data.datasets[0].needleValue;
                    const dataTotal = data.datasets[0].data.reduce((a,b)=>a+b,0);
                    const angle = Math.PI + (1 / dataTotal * needleValue * Math.PI)
                    const cx = width / 2;
                    const cy = chart._metasets[0].data[0].y;			
                    ctx.translate(cx,cy);
                    ctx.rotate(angle);
                    ctx.beginPath();
                    ctx.moveTo(0,-2);
                    ctx.lineTo(height - (ctx.canvas.offsetTop - 110),0);
                    ctx.lineTo(0,2);
                    ctx.fillStyle = "#FFF";
                    ctx.fill();
                    ctx.restore();
                    ctx.beginPath();
                    ctx.arc(cx,cy,5,0,10);	
                    ctx.fill();
                    ctx.restore();
                    ctx.font = '5px Helvetica';
                    ctx.margin = "30px 0 0 0";
                    ctx.fillStyle = '#FFF';
                    ctx.fillText(needleValue +'%',cx,cy);
                    ctx.textAlign = 'center';
                    ctx.restore();
                }
            }]
        }
        );    


        let previsao = $("#previsao");
        new Chart(
            previsao,
            {
                type: 'doughnut',
                data: {
                    labels: ["Ruim","Regular","Bom","Otimo"],
                    datasets: [{
                        label: 'Previsao',
                        data: [25,25,25,25],
                        backgroundColor: [
                            'rgba(255, 26, 104, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(50, 205, 50, 1)',
                        ],
		                needleValue:65,
                        borderColor: 'white',
                        borderWidth: 2,
                        cutout:'95%',
                        circumference:180,
                        rotation:270,
                        borderRadius:5
                    }]
                },
                options: {
                    scales: {},
		            plugins: {}
	        },
	        plugins: [{
		        afterDatasetDraw(chart,args,options) {
                    const { ctx,config,data,chartArea: {top,bottom,left,right,width,height} } = chart;
                    ctx.save();		
                    const needleValue = data.datasets[0].needleValue;
                    const dataTotal = data.datasets[0].data.reduce((a,b)=>a+b,0);
                    const angle = Math.PI + (1 / dataTotal * needleValue * Math.PI)
                    const cx = width / 2;
                    const cy = chart._metasets[0].data[0].y;			
                    ctx.translate(cx,cy);
                    ctx.rotate(angle);
                    ctx.beginPath();
                    ctx.moveTo(0,-2);
                    ctx.lineTo(height - (ctx.canvas.offsetTop - 110),0);
                    ctx.lineTo(0,2);
                    ctx.fillStyle = "#FFF";
                    ctx.fill();
                    ctx.restore();
                    ctx.beginPath();
                    ctx.arc(cx,cy,5,0,10);	
                    ctx.fill();
                    ctx.restore();
                    ctx.font = '5px Helvetica';
                    ctx.margin = "30px 0 0 0";
                    ctx.fillStyle = '#FFF';
                    ctx.fillText(needleValue +'%',cx,cy);
                    ctx.textAlign = 'center';
                    ctx.restore();
                }
            }]
        }
        );    

           
        $(".grafico").on('click',function(){
            $("html, body").animate({ scrollTop: "785px" },1000);
        }); 

        $(".anual").on('click',function(){
            $("html, body").animate({ scrollTop: "1500px" },1000);
        });

    });  



      
    
    
    

    </script>
@stop        




