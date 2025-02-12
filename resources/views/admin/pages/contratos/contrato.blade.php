@extends('adminlte::page')
@section('title', 'Contrato')
@section('plugins.jqueryUi', true)
@section('plugins.Toastr', true)
@section('plugins.Datatables', true)
@section('content_header')
        
    <ul class="list_abas">
        <li data-id="aba_individual" class="ativo">Individual</li>
        <li data-id="aba_coletivo">Coletivo</li>
       
        
    </ul>

@stop

@section('content_top_nav_right')
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    <!-- <li class="nav-item"><a href="" class="nav-link div_info"><i class="fas fa-cogs text-white"></i></a></li> -->
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content')

    <input type="hidden" id="janela_ativa" name="janela_ativa" value="aba_individual">



    <div class="container_div_info">
        
    </div>

    <section class="conteudo_abas">
        <!--------------------------------------INDIVIDUAL------------------------------------------>
        <main id="aba_individual">
           
            <section class="d-flex justify-content-between" style="flex-wrap: wrap;align-content: flex-start;">
            
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">                    

                    <div class="mb-1">
                        <!-- <button class="btn btn-success btn-block estilo_btn_plus_individual">Criar Contrato</button> -->
                        <!-- <a class="btn btn-block" style="background-color:#123449;color:#FFF;" href="{{route('contrato.create')}}">Criar Contrato</a> -->
                        <button class="btn btn-block" style="background-color:#123449;color:#FFF;">Listar Contrato</button>
                    </div>

                    

                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">

                        <div class="text-center py-1 d-flex justify-content-between border-bottom textoforte-list" id="all_pendentes_individual">
                            <span class="w-50 d-flex justify-content-start ml-2">
                                Contratos
                            </span>
                            <span class="d-flex justify-content-end badge badge-light mr-1 individual_quantidade_pendentes" style="width:45px;text-align:right;">
                                {{$qtd_individual_pendentes}}
                            </span>
                        </div>

                        <ul style="margin:0;padding:0;list-style:none;" id="listar_individual">
                           

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_1_parcela_individual" class="individual">
                                <span>Pag. 1º Parcela</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_parcela_01}}</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_2_parcela_individual" class="individual">
                               <span>Pag. 2º Parcela</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_parcela_02}}</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_3_parcela_individual" class="individual">
                               <span>Pag. 3º Parcela</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_parcela_03}}</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_4_parcela_individual" class="individual">
                               <span>Pag. 4º Parcela</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_parcela_04}}</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_5_parcela_individual" class="individual">
                               <span>Pag. 5º Parcela</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_parcela_05}}</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="aguardando_pagamento_6_parcela_individual" class="individual">
                               <span>Pag. 6º Parcela</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_parcela_06}}</span>                        
                            </li>

                            
                        </ul>
                    </div>


                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">
                        <ul style="list-style:none;margin:0;padding:10px 0;" id="grupo_individual_concluido">

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="finalizado_individual" class="individual">
                               <span>Finalizado</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_finalizado}}</span>                        
                            </li>

                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="cancelado_individual" class="individual">
                               <span>Cancelado</span>
                               <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_individual_cancelado}}</span>                        
                            </li>
                        </ul>
                    </div> 
                    
                    <div style="background-color:red;border-radius:5px;margin-top:5px;">   
                        <ul style="list-style:none;margin:0;padding:10px 0;" id="atrasado_corretor">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="" class="individual">
                                <span>Atrasados</span>
                                <span class="badge badge-light individual_quantidade_atrasado" style="width:45px;text-align:right;">{{$qtd_individual_atrasado}}</span>  
                            </li>
                        </ul>
                    </div>    






                </div>
                <!--Fim Coluna da Esquerda  -->

                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:83%;">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                        <table id="tabela_individual" class="table table-sm listarindividual">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Orçamento</th>
                                    <th>Cliente</th>
                                    <th>CPF</th>
                                    <th>Vidas</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>                                  
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div>
                </div>  
                <!--FIM COLUNA DA CENTRAL-->

                <!---------DIREITA-------------->    
                
                <!---------FIM DIREITA-------------->    
            </section>
       </main><!-------------------------------------DIV FIM Individial------------------------------------->     
       <!-------------------------------------FIM Individial------------------------------------->

       <!------------------------------------------COLETIVO---------------------------------------------------->
       <main id="aba_coletivo" class="ocultar">
             <section class="d-flex justify-content-between" style="flex-wrap: wrap;">
                <!--COLUNA DA ESQUERDA-->
                <div class="d-flex flex-column text-white ml-1" style="flex-basis:16%;border-radius:5px;">
                    <div class="d-flex flex-column">
                       <div class="text-white" style="flex-basis:10%;">
                            <!-- <button class="estilo_btn_plus_coletivo btn btn-success btn-block">Criar Contrato</button> -->
                            <a href="{{route('contratos.create.coletivo.corretor')}}" class="btn btn-block" style="background-color:#123449;color:#FFF;">Criar Contrato</a>
                        </div>
                        <select class="my-1 form-control" style="flex-basis:80%;" id="select_coletivo">
                            <option value="todos" class="text-center">---Administradora---</option>      
                        </select>
                        
                    </div>
                    
                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">
                        <div class="text-center py-1 d-flex justify-content-between border-bottom textoforte-list" id="all_pendentes_coletivo">
                            <span class="w-50 d-flex justify-content-start ml-2">Contratos</span>
                            <span class="d-flex justify-content-end badge badge-light mr-1 coletivo_quantidade_pendentes" style="width:45px;text-align:right;">
                                {{$contratos_coletivo_pendentes}}
                            </span>
                        </div>
                        <ul style="margin:0;padding:0;list-style:none;" id="listar">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="em_analise_coletivo" class="coletivo">
                                <span>Em Analise</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;vertical-align: middle;">{{$qtd_coletivo_em_analise}}</span>                        
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="emissao_boleto_coletivo" class="coletivo">
                                <span>Emissão Boleto</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_emissao_boleto}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_adesao_coletivo" class="coletivo">
                                <span>Pag. Adesão</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_pg_adesao}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_vigencia_coletivo" class="coletivo">
                                <span>Pag. Vigência</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_pg_vigencia}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_segunda_parcela" class="coletivo">
                                <span>Pag. 2º Parcela</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_02_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_terceira_parcela" class="coletivo">
                                <span>Pag. 3º Parcela</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_03_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_quarta_parcela" class="coletivo">
                                <span>Pag. 4º Parcela</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_04_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_quinta_parcela" class="coletivo">
                                <span>Pag. 5º Parcela</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_05_parcela}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="pagamento_sexta_parcela" class="coletivo">
                                <span>Pag. 6º Parcela</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_06_parcela}}</span>
                            </li>
                            
                        </ul>
                    </div>

                    <div style="margin:0 0 20px 0;padding:0;background-color:#123449;border-radius:5px;">
                        <ul style="list-style:none;margin:0;padding:10px 0;" id="grupo_coletivo_concluido">
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="finalizado_coletivo" class="coletivo">
                                <span>Finalizado</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_finalizados}}</span>
                            </li>
                            <li style="padding:0px 5px;display:flex;justify-content:space-between;margin-bottom:5px;" id="cancelado_coletivo" class="coletivo">
                                <span>Cancelados</span>
                                <span class="badge badge-light" style="width:45px;text-align:right;">{{$qtd_coletivo_cancelados}}</span>
                            </li>   
                        </ul>
                    </div>





                </div>    
                <!--FIM COLUNA DA ESQUERDA-->


                <!--COLUNA DA CENTRAL-->
                <div style="flex-basis:83%;">
                    <div class="p-2" style="background-color:#123449;color:#FFF;border-radius:5px;">
                        <table id="tabela_coletivo" class="table table-sm listardados">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Administradora</th>
                                    <th>Cliente</th>
                                    <th>CPF</th>
                                    <th>Vidas</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>   
                    </div> 
                </div>  
                <!--FIM COLUNA DA CENTRAL-->


                <!--COLUNA DA DIREITA-->    
                

            </section>

       </main>

      
    </section>
   
@stop  

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>   
   
    <script>
        $(function(){
            var default_formulario = $('.coluna-right.aba_individual').html();
            let url = window.location.href.indexOf("?");
            if(url != -1) {
                var b =  window.location.href.substring(url);
                var alvo = b.split("=")[1];
                if(alvo == "coletivo") {
                    $('.list_abas li').removeClass('ativo');
                    $('.list_abas li:nth-child(2)').addClass("ativo");
                    $('.conteudo_abas main').addClass('ocultar');
                    $('#aba_coletivo').removeClass('ocultar');
                    $("#janela_ativa").val("aba_coletivo");
                    var c = window.location.href.replace(b,"");
                    window.history.pushState({path:c},'',c);
                } 
                
            }

            String.prototype.ucWords = function () {
                let str = this.toLowerCase()
                let re = /(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g
                return str.replace(re, s => s.toUpperCase())
            }

            $('#cadastrarIndividualModal').on('hidden.bs.modal', function (event) {
                $('#cadastrar_pessoa_fisica_formulario_individual').each(function(){
                    this.reset();
                });
            })

            $('#cadastrarContratoModal').on('hidden.bs.modal', function (event) {
                $('#cadastrar_pessoa_fisica_formulario_modal_coletivo').each(function(){
                    this.reset();
                });
            });

            $("body").on('change','#dependente_individual',function(){
                if($(this).is(':checked')) {
                    $("#container_responsavel").removeClass('d-none');
                } else {
                    $("#container_responsavel").addClass('d-none');
                }
            });
            
            $("body").on('change','#dependente_coletivo',function(){
               if($(this).is(':checked')) {
                    $("#container_responsavel_coletivo").removeClass('d-none');
                } else {
                    $("#container_responsavel_coletivo").addClass('d-none');
                } 
            });

            $("#all_pendentes_individual").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pendentes</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes.contrato.corretor') }}").load();
                $("ul#listar_individual li.individual").removeClass('textoforte-list');                
                $("#grupo_individual_concluido li.individual").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
            });

            $("#all_pendentes_coletivo").on('click',function(){
                $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pendentes</h4>");
                table.ajax.url("{{ route('financeiro.individual.geralColetivoPendentes.contrato.corretor') }}").load();
                $("ul#listar li.coletivo").removeClass('textoforte-list');
                $("#grupo_coletivo_concluido li.individual").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');
            });            

            $(".list_abas li").on('click',function(){ 
                $('li').removeClass('ativo');
                $(this).addClass("ativo");
                let id = $(this).attr('data-id');
                $("#janela_ativa").val(id);
                default_formulario = $('.coluna-right.'+id).html();
                $('.conteudo_abas main').addClass('ocultar');
                $('#'+id).removeClass('ocultar');
                $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pendentes</h4>");
                table.ajax.url("{{ route('financeiro.individual.geralColetivoPendentes.contrato.corretor') }}").load();
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pendentes</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.geralIndividualPendentes.contrato.corretor') }}").load();
                $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');  
                $("ul#listar li.coletivo").removeClass('textoforte-list');
                $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                $("#all_pendentes_individual").addClass("textoforte-list");
                $("#all_pendentes_coletivo").addClass("textoforte-list");
                limparFormularioIndividual();
                limparFormulario();
            });

            $('#cnpj').mask('00.000.000/0000-00');
            $('#telefone_individual').mask('0000-0000');
            $('#celular_individual').mask('(00) 0 0000-0000');
            $('#celular').mask('(00) 0 0000-0000');
            $('#taxa_adesao').mask("#.##0,00", {reverse: true});
            $('#valor_plano').mask("#.##0,00", {reverse: true});
            $('#valor_total').mask("#.##0,00", {reverse: true});
            $('#valor_boleto').mask("#.##0,00", {reverse: true});
            $('#valor_plano_saude').mask("#.##0,00", {reverse: true});
            $('#valor_plano_saude').mask("#.##0,00", {reverse: true});
            $('#valor_plano_odonto').mask("#.##0,00", {reverse: true});
            $('#cpf_individual').mask('000.000.000-00');
            $('#cpf_financeiro_individual_cadastro').mask('000.000.000-00');            
            $('#cpf_coletivo').mask('000.000.000-00');  
            $('#cep_individual').mask('00000-000');          
            $('#cep_coletivo').mask('00000-000');          
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });   

            var taindividual = $(".listarindividual").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_individual"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('financeiro.individual.geralIndividualPendentes.contrato.corretor') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"created_at",name:"data",},
                    {
                        data:"codigo_externo",name:"codigo_externo"
                    },
                    {data:"clientes.nome",name:"cliente"},
                    {data:"clientes.cpf",name:"cpf",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let cpf = cellData.substr(0,3)+"."+cellData.substr(3,3)+"."+cellData.substr(6,3)+"-"+cellData.substr(9,2);
                            $(td).html(cpf);
                        }
                    },
                    {data:"clientes.quantidade_vidas",name:"vidas",
                        
                    },
                    {
                        data:"valor_plano",name:"valor_plano",
                        render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')
                    },
                    {data:"comissao.comissao_atual_financeiro",name:"vencimento",
                        "createdCell": function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                if(rowData.financeiro.id == 10) {                                    
                                    let alvo = rowData.comissao.comissao_atual_last.data.split("-").reverse().join("/");
                                    $(td).html(alvo);    
                                } else if(rowData.financeiro.id == 11) {
                                    $(td).html("Finalizado");
                                } else {
                                    $(td).html("Cancelado");    
                                }

                            } else {
                                let alvo = cellData.data.split("-").reverse().join("/");
                                $(td).html(alvo);
                            }
                        }
                    },   
                    {data:"financeiro.nome",name:"financeiro"},
                    {data:"financeiro.nome",name:"detalhes"}
                ],
                "columnDefs": [
                    {
                        /** Data*/
                        "targets": 0,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        },
                        "width":"8%"
                    },
                   
                    {
                        "targets": 1,
                        "width":"10%",
                       
                    },
                    {
                        "targets": 2,
                        "width":"30%",
                        "createdCell":function(td,cellData,rowData,row,col) {
                            let palavras = cellData.ucWords();
                            $(td).html(palavras)
                        }
                        // "createdCell": function (td, cellData, rowData, row, col) {   
                        //     let alvo = cellData.split("-").reverse().join("/");
                        //     $(td).html(alvo);
                        // }       
                    },
                    {
                        "targets": 3,
                        "width":"10%"
                    },
                    {
                        "targets": 4,
                        "width":"5%"
                    },
                    {
                        "targets": 5,
                        "width":"10%"
                    },
                    {
                        "targets": 6,
                        "width":"5%"
                    },

                    {
                        "targets": 7,
                        "width":"10%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(cellData == "Pagamento 1º Parcela") {
                                $(td).html("Pag. 1º Parcela");        
                            }
                            if(cellData == "Pagamento 2º Parcela") {
                                $(td).html("Pag. 2º Parcela");        
                            }
                            if(cellData == "Pagamento 3º Parcela") {
                                $(td).html("Pag. 3º Parcela");        
                            }
                            if(cellData == "Pagamento 4º Parcela") {
                                $(td).html("Pag. 4º Parcela");        
                            }
                            if(cellData == "Pagamento 5º Parcela") {
                                $(td).html("Pag. 5º Parcela");        
                            }
                            if(cellData == "Pagamento 6º Parcela") {
                                $(td).html("Pag. 6º Parcela");        
                            }
                        },
                    },
                    {
                        "targets": 8,
                        "width":"10%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var id = rowData.id;
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/admin/financeiro/detalhes/${id}" class="text-white">  
                                            <i class='fas fa-eye div_info'></i>
                                        </a>
                                    </div>
                                `);


                            
                        }
                    }
               ],
                
                "initComplete": function( settings, json ) {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pendentes</h4>");                     
                },

                "drawCallback": function( settings ) {
                   
                }
            });

            $(".listardados").DataTable({
                dom: '<"d-flex justify-content-between"<"#title_coletivo_por_adesao_table"><"estilizar_search"f>><t><"d-flex justify-content-between align-items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                "language": {
                    "url": "{{asset('traducao/pt-BR.json')}}"
                },
                ajax: {
                    "url":"{{ route('financeiro.individual.geralColetivoPendentes.contrato.corretor') }}",
                    "dataSrc": ""
                },
                "lengthMenu": [50,100,150,200,300,500],
                "ordering": false,
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                columns: [
                    {data:"created_at",name:"data"},
                    {data:"administradora.nome",name:"administradora"},
                    {data:"clientes.nome",name:"cliente"},
                    {data:"clientes.cpf",name:"cpf"},
                    {data:"somar_cotacao_faixa_etaria[0].soma",name:"vidas"},
                    {data:"valor_plano",name:"valor_plano"},
                    {data:"comissao.comissao_atual_financeiro",name:"Vencimento",
                        "createdCell": function(td,cellData,rowData,row,col) {
                            if(cellData == null) {
                                if(rowData.financeiro.id == 10) {                                    
                                    let alvo = rowData.comissao.comissao_atual_last.data.split("-").reverse().join("/");
                                    $(td).html(alvo);    
                                } else if(rowData.financeiro.id == 11) {
                                    $(td).html("Finalizado");
                                } else {
                                    $(td).html("Cancelado");    
                                }
                            } else {
                                let alvo = cellData.data.split("-").reverse().join("/");
                                $(td).html(alvo);
                            }
                        },
                    },
                    {data:"financeiro.nome",name:"administradora"},
                    {data:"financeiro.nome",name:"ver"}
                ],
                "columnDefs": [
                    {
                        /** Data*/
                        "width":"8%",
                        "targets": 0,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            let datas = cellData.split("T")[0]
                            let alvo = datas.split("-").reverse().join("/")
                            $(td).html(alvo)    
                        }
                    },
                    
                    /*Administradora*/
                    {
                        "targets": 1,
                        "width":"10%"
                    },
                    /*Cliente*/
                    {
                        "targets": 2,
                        "width":"25%"
                    },
                    /*CPF*/
                    {
                        "width":"10%",
                        "targets": 3
                    },
                    /*Vidas*/
                    {
                        "targets": 4,
                        "width":"5%"
                    },
                    {
                        "targets": 5,
                        "width":"10%"
                    },
                    
                    {
                        "targets": 6,
                        "width":"10%"
                    },

                    {
                        "targets": 7,
                        "width":"10%",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(cellData == "Pagamento Adesão") {
                                $(td).html("Pag. Adesão");        
                            }
                            if(cellData == "Pagamento Vigência") {
                                $(td).html("Pag. Vigência");        
                            }
                            if(cellData == "Pagamento 2º Parcela") {
                                $(td).html("Pag. 2º Parcela");        
                            }
                            if(cellData == "Pagamento 3º Parcela") {
                                $(td).html("Pag. 3º Parcela");        
                            }
                            if(cellData == "Pagamento 4º Parcela") {
                                $(td).html("Pag. 4º Parcela");        
                            }
                            if(cellData == "Pagamento 5º Parcela") {
                                $(td).html("Pag. 5º Parcela");        
                            }
                            if(cellData == "Pagamento 6º Parcela") {
                                $(td).html("Pag. 6º Parcela");        
                            }
                        },
                    },
                    {
                        "width":"4%",
                        "targets": 8,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            // $(td).html("<div class='text-center'><i class='fas fa-eye div_info' data-id='"+rowData.id+"'></i></div>");
                            var id = rowData.id;
                                $(td).html(`<div class='text-center text-white'>
                                        <a href="/admin/financeiro/detalhes/${id}" class="text-white">  
                                            <i class='fas fa-eye div_info'></i>
                                        </a>
                                    </div>
                                `);
                        }
                    }
               ],              
                "initComplete": function( settings, json ) {
                    $('#title_coletivo_por_adesao_table').html("<h4>Coletivo Por Adesão</h4>");
                    this.api()
                       .columns([1])
                       .every(function () {
                            var column = this;
                            var selectAdministradora = $("#select_coletivo");
                            selectAdministradora.on('change',function(){
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                if(val != "todos") {
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();    
                                } else {
                                    var val = "";
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                } 
                            });
                            column.data().unique().sort().each(function (d, j) {
                                selectAdministradora.append('<option value="' + d + '">' + d + '</option>');
                            });
                       })
                },
                "drawCallback": function( settings ) {                    
                }
            });

            var table = $('#tabela_coletivo').DataTable();
            $('#tabela_coletivo').on('click', 'tbody tr', function () {
                table.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
                let data = table.row(this).data();
                
                // let acomodacao_individual = data.acomodacao.nome;
                // let coparticipacao_individual = data.coparticipacao;
                // let odonto_individual = data.odonto;
                let texto = "";

                if(acomodacao_individual == "Apartamento" && coparticipacao_individual == 1 && odonto_individual == 1) {
                    texto = "Apartamento C/Copart + Odonto";
                } else if(acomodacao_individual == "Apartamento" && coparticipacao_individual == 1 && odonto_individual == 0) {
                    texto = "Apartamento C/Copart Sem Odonto";
                } else if(acomodacao_individual == "Apartamento" && coparticipacao_individual == 0 && odonto_individual == 0) {
                    texto = "Apartamento S/Copart Sem Odonto";
                } else if(acomodacao_individual == "Enfermaria" && coparticipacao_individual == 1 && odonto_individual == 1) {
                    texto = "Enfermaria C/Copart + Odonto";    
                } else if(acomodacao_individual == "Enfermaria" && coparticipacao_individual == 1 && odonto_individual == 0) {
                    texto = "Enfermaria C/Copart Sem Odonto";    
                } else if(acomodacao_individual == "Enfermaria" && coparticipacao_individual == 0 && odonto_individual == 0) {
                    texto = "Apartamento S/Copart Sem Odonto";    
                } else {
                    texto = "";
                }
                $("#texto_descricao_coletivo_view").val(texto);

                if(data.clientes.dependente) {
                    $("#responsavel_financeiro_coletivo").val(data.clientes.dependentes.nome);
                    $("#cpf_financeiro_coletivo_view").val(data.clientes.dependentes.cpf);
                } else {
                    $("#responsavel_financeiro_coletivo").val('');
                    $("#cpf_financeiro_coletivo_view").val('');
                }
                ///$('.div_info').attr('data-id',data.id);
                let criacao = data.created_at.split("T")[0].split("-").reverse().join("/");                
                let nascimento = data.clientes.data_nascimento.split("T")[0].split("-").reverse().join("/");
                let data_vigencia = data.data_vigencia.split("T")[0].split("-").reverse().join("/");
                let data_boleto = data.data_boleto.split("T")[0].split("-").reverse().join("/");
                let valor_contrato = Number(data.valor_plano).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                let valor_adesao = Number(data.valor_adesao).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});   
                $("#complemento_coletivo_view").val(data.clientes.complemento);
                $("#cliente_coletivo_view").val(data.clientes.nome);
                $("#cidade_coletivo_view").val(data.clientes.cidade);
                $("#estagio_contrato_coletivo_view").val(data.financeiro.nome);
                $("#telefone_coletivo_view").val(data.clientes.telefone);
                $("#celular_coletivo_view").val(data.clientes.celular);
                $("#email_coletivo_view").val(data.clientes.email);
                $("#data_nascimento_coletivo_view").val(nascimento);
                $("#cpf_coletivo_view").val(data.clientes.cpf);
                $("#cep_coletivo_view").val(data.clientes.cep);
                $("#bairro_coletivo_view").val(data.clientes.bairro)
                $("#rua_coletivo_view").val(data.clientes.rua);
                $("#uf_coletivo_view").val(data.clientes.uf);
                $("#administradora_coletivo_view").val(data.administradora.nome);
                $("#codigo_externo_coletivo_view").val(data.codigo_externo);              
                $("#data_contrato_coletivo_view").val(criacao);
                $("#valor_contrato_coletivo_view").val(valor_contrato);
                $("#data_vigencia_coletivo_view").val(data_vigencia);
                $("#data_boleto_coletivo_view").val(data_boleto);
                $("#valor_adesao_coletivo_view").val(valor_adesao);
                $("#coparticipacao_sim").attr("style","padding:0.21rem 0.75rem;");
                $("#coparticipacao_nao").attr("style","padding:0.21rem 0.75rem;");
                $("#odonto_sim").attr("style","padding:0.21rem 0.75rem;");
                $("#odonto_nao").attr("style","padding:0.21rem 0.75rem;");
                $("#desconto_corretora_coletivo").val(Number(data.desconto_corretora).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                $("#desconto_corretor_coletivo").val(Number(data.desconto_corretor).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                let desconto = data.valor_plano - data.valor_adesao;
                $("#desconto_coletivo").val(Number(desconto).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})); 

                if(data.coparticipacao) {       
                    $("#coparticipacao_sim_coletivo").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                } else {
                    $("#coparticipacao_nao_coletivo").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                }
                if(data.odonto) {
                    $("#odonto_sim_coletivo").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                } else {
                    $("#odonto_nao_coletivo").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                }
                // $("#quantidade_vidas").val(vidas);
                $("#tipo_plano_coletivo_view").val(data.plano.nome);  
                $("#quantidade_vidas_coletivo_cadastrar").val(data.somar_cotacao_faixa_etaria[0].soma);                
                //comissoes_premiacoes(data.id,data.financeiro_id)
            });

            var table_individual = $('#tabela_individual').DataTable();
            $('#tabela_individual').on('click', 'tbody tr', function () {
                table_individual.$('tr').removeClass('textoforte');
                $(this).closest('tr').addClass('textoforte');
                let data = table_individual.row(this).data();   
                
                // let acomodacao_individual = data.acomodacao.nome;
                // let coparticipacao_individual = data.coparticipacao;
                // let odonto_individual = data.odonto;
                // let texto = "";

                // if(acomodacao_individual == "Apartamento" && coparticipacao_individual == 1 && odonto_individual == 1) {
                //     texto = "Apartamento C/Copart + Odonto";
                // } else if(acomodacao_individual == "Apartamento" && coparticipacao_individual == 1 && odonto_individual == 0) {
                //     texto = "Apartamento C/Copart Sem Odonto";
                // } else if(acomodacao_individual == "Apartamento" && coparticipacao_individual == 0 && odonto_individual == 0) {
                //     texto = "Apartamento S/Copart Sem Odonto";
                // } else if(acomodacao_individual == "Enfermaria" && coparticipacao_individual == 1 && odonto_individual == 1) {
                //     texto = "Enfermaria C/Copart + Odonto";    
                // } else if(acomodacao_individual == "Enfermaria" && coparticipacao_individual == 1 && odonto_individual == 0) {
                //     texto = "Enfermaria C/Copart Sem Odonto";    
                // } else if(acomodacao_individual == "Enfermaria" && coparticipacao_individual == 0 && odonto_individual == 0) {
                //     texto = "Apartamento S/Copart Sem Odonto";    
                // } else {
                //     texto = "";
                // }
                // $("#texto_descricao_individual_view").val(texto)

                if(data.clientes.dependente) {
                    $("#responsavel_financeiro").val(data.clientes.dependentes.nome);
                    $("#cpf_financeiro").val(data.clientes.dependentes.cpf);
                } else {
                    $("#responsavel_financeiro").val('');
                    $("#cpf_financeiro").val('');
                }

                if(data.comissao.cancelado) {
                    let data_cancelado = data.comissao.cancelado.data_baixa.split("-").reverse().join("/");

                    $("#observacao_cancelamento").val(data.comissao.cancelado.observacao);
                    $("#data_cancelamento").val(data_cancelado);

                    if(data.comissao.cancelado.motivo == 1) {
                        $("#motivo_cancelamento").val('Dados Incorretos');
                    } else if(data.comissao.cancelado.motivo == 2) {
                        $("#motivo_cancelamento").val('Cliente Desistiu do Plano');
                    } else if(data.comissao.cancelado.motivo == 3) {
                        $("#motivo_cancelamento").val('Cliente Trocou de Plano');
                    } else {
                        $("#motivo_cancelamento").val('Sem Espeficicação');
                    }



                } else {
                    $("#observacao_cancelamento").val('');
                    $("#data_cancelamento").val('');
                    $("#motivo_cancelamento").val('');
                }

                //$('.div_info').attr('data-id',data.id);
                $('.container_div_info').hide();
                let criacao = data.created_at.split("T")[0].split("-").reverse().join("/");                
                let nascimento = data.clientes.data_nascimento.split("T")[0].split("-").reverse().join("/");
                let data_vigencia = data.data_vigencia.split("T")[0].split("-").reverse().join("/");
                let data_boleto = data.data_boleto.split("T")[0].split("-").reverse().join("/");
                let valor_contrato = Number(data.valor_plano).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                let valor_adesao = Number(data.valor_adesao).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});       
                $("#cliente").val(data.clientes.nome);
                $("#cpf").val(data.clientes.cpf);
                $("#cidade").val(data.clientes.cidade);
                $("#status_individual_view").val(data.financeiro.nome);
                $("#telefone").val(data.clientes.celular);
                $("#email").val(data.clientes.email);
                $("#data_nascimento").val(nascimento);
                $("#cpf_coletivo").val(data.clientes.cpf);
                $("#cep_individual_cadastro").val(data.clientes.cep);
                $("#bairro_individual_cadastro").val(data.clientes.bairro)
                $("#rua_individual_cadastro").val(data.clientes.rua);
                $("#uf").val(data.clientes.uf);
                $("#administradora_individual").val(data.administradora.nome);
                $("#codigo_externo_individual").val(data.codigo_externo);              
                $("#data_contrato").val(criacao);
                $("#valor_contrato").val(valor_contrato);
                $("#data_vigencia").val(data_vigencia);
                $("#data_boleto").val(data_boleto);
                $("#valor_adesao").val(valor_adesao);
                $("#complemento_individual_cadastro").val(data.clientes.complemento);
                $("#celular_individual_view_input").val(data.clientes.celular);
                $("#telefone_individual_view_input").val(data.clientes.telefone);
                $("#coparticipacao_sim").attr("style","padding:0.21rem 0.75rem;");
                $("#coparticipacao_nao").attr("style","padding:0.21rem 0.75rem;");
                $("#odonto_sim").attr("style","padding:0.21rem 0.75rem;");
                $("#odonto_nao").attr("style","padding:0.21rem 0.75rem;");
                if(data.coparticipacao) {       
                    $("#coparticipacao_sim").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                } else {
                    $("#coparticipacao_nao").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                }
                if(data.odonto) {
                    $("#odonto_sim").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                } else {
                    $("#odonto_nao").attr("style","padding:0.21rem 0.75rem;background-color:white;color:black;").attr("disabled",true);
                }
                // $("#quantidade_vidas").val(vidas);
                $("#tipo_plano_individual").val(data.plano.nome); 
                $("#quantidade_vidas_individual_cadastrar").val(data.somar_cotacao_faixa_etaria[0].soma);                
            });



            // $("body").on('mouseover','.div_info',function(){
            //    let contrato = $(this).attr('data-id');
            //    let janela_ativa = $('#janela_ativa').val(); 
            //    $.ajax({
            //         url:"{{route('contratos.info')}}",
            //         data:"contrato="+contrato,
            //         method:"POST",
            //         success:function(res) {
            //             $('.coluna-right.'+janela_ativa).html(res);
            //             //$('.container_div_info').html(res);
            //         }
            //     });
            //     $('.container_div_info').toggle();
            //     return false;
            // });

            // $("body").on('mouseout','.div_info',function(){
            //     let janela_ativa = $('#janela_ativa').val();
            //     $(".coluna-right."+janela_ativa).html(default_formulario);
            // });

            //  $("select[name='mudar_user_empresarial']").on('change',function(e){
            //     let user = $(this).val();
            //     $.ajax({
            //         url:"{{route('contratos.listarEmpresarialPorUser')}}",
            //         method:"POST",
            //         data:"user="+user,
            //         success:function(res) {
            //             tableempresarial.ajax.url(res).load();
            //         }
            //     });      
                
            // });

            $("ul#listar li.coletivo").on('click',function(){
                let id_lista = $(this).attr('id');
                
                if(id_lista == "em_analise_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.em_analise.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    limparFormulario()
                } else if(id_lista == "emissao_boleto_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Emissão Boleto</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.emissao_boleto.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    limparFormulario()
                } else if(id_lista == "pagamento_adesao_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento Adesão</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_adesao.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    limparFormulario()
                } else if(id_lista == "pagamento_vigencia_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento Vigência</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_vigencia.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list'); 
                    limparFormulario();   
                } else if(id_lista == "pagamento_segunda_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 2º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_segunda_parcela.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list'); 
                    limparFormulario();  
                } else if(id_lista == "pagamento_terceira_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 3º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_terceira_parcela.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    limparFormulario();
                } else if(id_lista == "pagamento_quarta_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 4º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_quarta_parcela.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    limparFormulario();
                } else if(id_lista == "pagamento_quinta_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 5º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_quinta_parcela.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');     
                    limparFormulario();
                } else if(id_lista == "pagamento_sexta_parcela") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 6º Parcela</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.pagamento_sexta_parcela.corretor') }}").load();
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("#all_pendentes_coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');     
                    limparFormulario();
                } else {

                }
            });      

            $("ul#listar_individual li.individual").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "aguardando_em_analise_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Em Análise</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.em_analise.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');   
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else if(id_lista == "aguardando_pagamento_1_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 1º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_primeira_parcela.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');  
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');     
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else if(id_lista == "aguardando_pagamento_2_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 2º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_segunda_parcela.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');    
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');   
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else if(id_lista == "aguardando_pagamento_3_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 3º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_terceira_parcela.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');      
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else if(id_lista == "aguardando_pagamento_4_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 4º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_quarta_parcela.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list'); 
                    $("#all_pendentes_individual").removeClass('textoforte-list');     
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else if(id_lista == "aguardando_pagamento_5_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 5º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_quinta_parcela.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list'); 
                    $("#all_pendentes_individual").removeClass('textoforte-list');     
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else if(id_lista == "aguardando_pagamento_6_parcela_individual") {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Pagamento 6º Parcela</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.pagamento_sexta_parcela.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list');
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');    
                    $("#all_pendentes_individual").removeClass('textoforte-list');   
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                }  else {

                }
            });  
           
            
            $("ul#grupo_individual_concluido li.individual").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "finalizado_individual") { 
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.finalizado.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").addClass("ocultar");
                    limparFormularioIndividual();
                } else {
                    $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
                    table_individual.ajax.url("{{ route('financeiro.individual.cancelado.corretor') }}").load();
                    $("ul#listar_individual li.individual").removeClass('textoforte-list'); 
                    $("ul#grupo_individual_concluido li.individual").removeClass('textoforte-list');
                    $("#all_pendentes_individual").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                    $(".dados_cancelados").removeClass("ocultar");
                    limparFormularioIndividual();
                }    
            });

            $("#atrasado_corretor").on('click',function(){
                $('#title_individual').html("<h4 style='font-size:1em;margin-top:10px;'>Atrasado</h4>");
                table_individual.ajax.url("{{ route('financeiro.individual.atrasado.corretor') }}").load();
                $("ul#listar_individual li.individual").removeClass('textoforte-list');
                $("#all_pendentes_individual").removeClass('textoforte-list');
                $("ul#grupo_finalizados_individual li.individual").removeClass('textoforte-list');
                $(this).addClass('textoforte-list');



            });

            $("ul#grupo_coletivo_concluido li.coletivo").on('click',function(){
                let id_lista = $(this).attr('id');
                if(id_lista == "finalizado_coletivo") {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Finalizado</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.finalizado.corretor') }}").load();
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                } else {
                    $('#title_coletivo_por_adesao_table').html("<h4 style='font-size:1em;margin-top:10px;'>Cancelado</h4>");
                    table.ajax.url("{{ route('financeiro.coletivo.cancelado.corretor') }}").load();
                    $("ul#grupo_coletivo_concluido li.coletivo").removeClass('textoforte-list');
                    $("ul#listar li.coletivo").removeClass('textoforte-list');
                    $(this).addClass('textoforte-list');
                }
            });


            function limparTudo() {
                $('.coluna-right').find("input[type='text']").val('');
                $('tr').removeClass('textoforte');
                $('li').prop('data-id','');
                $('select').prop('selectedIndex',0);
            }

            function limparFormularioIndividual() {
                $("#administradora_individual").val('');
                $("#tipo_plano_individual").val('');
                $("#status_individual_view").val('');
                $("#cliente").val('');
                $("#data_nascimento").val('');
                $("#codigo_externo_individual").val('');
                $("#cpf").val('');
                $("#responsavel_financeiro").val('');
                $("#cpf_financeiro").val('');
                $("#celular_individual_view_input").val('');
                $("#telefone_individual_view_input").val('');
                $("#email").val('');
                $("#cep_individual_cadastro").val('');
                $("#cidade").val('');
                $("#uf").val('');
                $("#bairro_individual_cadastro").val('');
                $("#rua_individual_cadastro").val('');
                $("#complemento_individual_cadastro").val('');
                $("#data_contrato").val('');
                $("#valor_contrato").val('');
                $("#valor_adesao").val('');
                $("#quantidade_vidas_individual_cadastrar").val('');
                $("#data_boleto").val('');
                $("#data_vigencia").val('');
                $("#texto_descricao_individual_view").val('');
            }

            function limparFormulario() {
                $("#administradora_coletivo_view").val('');
                $("#tipo_plano_coletivo_view").val('');
                $("#estagio_contrato_coletivo_view").val('');
                $("#cliente_coletivo_view").val('');
                $("#data_nascimento_coletivo_view").val('');
                $("#codigo_externo_coletivo_view").val('');
                $("#cpf_coletivo_view").val('');
                $("#responsavel_financeiro_coletivo").val('');
                $("#cpf_financeiro_coletivo_view").val('');
                $("#celular_coletivo_view").val('');
                $("#telefone_coletivo_view").val('');
                $("#email_coletivo_view").val('');
                $("#cep_coletivo_view").val('');
                $("#cidade_coletivo_view").val('');
                $("#uf_coletivo_view").val('');
                $("#bairro_coletivo_view").val('');
                $("#rua_coletivo_view").val('');
                $("#complemento_coletivo_view").val('');
                $("#data_contrato_coletivo_view").val('');
                $("#valor_contrato_coletivo_view").val('');
                $("#valor_adesao_coletivo_view").val('');
                $("#quantidade_vidas_coletivo_cadastrar").val('');
                $("#data_boleto_coletivo_view").val('');
                $("#data_vigencia_coletivo_view").val('');
                $("#texto_descricao_coletivo_view").val('');
            }



        });
    </script>
@stop

@section('css')
    <style>
        .ativo {background-color:#FFF !important;color: #000 !important;}
        .ocultar {display: none;}
        .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
        .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
        .list_abas li:hover {cursor: pointer;}    
        .list_abas li:nth-of-type(2) {margin: 0 1%;}
        .textoforte {background-color:rgba(255,255,255,0.5) !important;color:black;}
        .textoforte-list {background-color:rgba(255,255,255,0.5);color:white;}
        .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;}
        .valores-acomodacao {background-color:#123449;color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;}
        .valores-acomodacao:hover {cursor:pointer;box-shadow: none;}
        .table thead tr {background-color:#123449;color: white;}
        .destaque {border:4px solid rgba(36,125,157);}
        #coluna_direita {flex-basis:10%;background-color:#123449;border-radius: 5px;}
        #coluna_direita ul {list-style: none;margin: 0;padding: 0;}
        #coluna_direita li {color:#FFF;}
        .coluna-right {flex-basis:30%;flex-wrap: wrap;border-radius:5px;height:720px;}
        /* .container_div_info {background-color:rgba(0,0,0,1);position:absolute;width:450px;right:0px;top:57px;min-height: 700px;display: none;z-index: 1;color: #FFF;} */
        .container_div_info {display:flex;position:absolute;flex-basis:30%;right:0px;top:57px;display: none;z-index: 1;color: #FFF;}


        #padrao {width:50px;background-color:#FFF;color:#000;}

        th { font-size: 0.9em !important; }
        td { font-size: 0.9em !important; }        
        
        .dt-right {text-align: right !important;}
        .dt-center {text-align: center !important;}
        .estilizar_pagination .pagination {font-size: 0.8em !important;color:#FFF;}
        .estilizar_pagination .pagination li {height:10px;color:#FFF;}
        .por_pagina {font-size: 12px !important;color:#FFF;}
        .por_pagina #tabela_mes_atual_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina #tabela_mes_diferente_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
        .por_pagina select {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_atual_previous {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_atual_next {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_diferente_previous {color:#FFF !important;}
        .estilizar_pagination #tabela_mes_diferente_next {color:#FFF !important;}
        .estilizar_search input[type='search'] {background-color: #FFF !important;}

    </style>
@stop




